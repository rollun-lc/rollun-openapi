<?php
declare(strict_types=1);

namespace OpenAPI\Server\Producer;

use Articus\DataTransfer\Service as DTService;
use Articus\PathHandler\Exception\HttpCode;
use Articus\PathHandler\Producer\Transfer as Base;
use OpenAPI\Server\Writer\Messages;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Transfer
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Transfer extends Base
{
    // @todo ErrorResult

    const KEY_MESSAGES = 'messages';
    const KEY_LEVEL = Messages::KEY_LEVEL;
    const KEY_MESSAGE = Messages::KEY_MESSAGE;

    const TYPE_ERROR = LogLevel::ERROR;

    /**
     * @var string|null
     */
    protected $responseType;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Transfer constructor.
     *
     * @param callable    $streamFactory
     * @param DTService   $dtService
     * @param DTService   $logger
     * @param null        $mapper
     * @param string|null $responseType
     */
    public function __construct(callable $streamFactory, DTService $dtService, LoggerInterface $logger, $mapper = null, $responseType = null)
    {
        parent::__construct($streamFactory, $dtService);

        $this->responseType = $responseType;
        $this->logger = $logger;
    }

    /**
     * @param array $messages
     *
     * @return array
     */
    public static function getErrorMessages(array $messages): array
    {
        foreach ($messages as $row) {
            if (!isset($row[self::KEY_LEVEL]) || !isset($row[self::KEY_MESSAGE])) {
                throw new \InvalidArgumentException('Invalid message array');
            }
        }

        return [self::KEY_MESSAGES => $messages];
    }

    /**
     * @param string $value
     *
     * @return array
     */
    public static function getSingleErrorMessages(string $value): array
    {
        return self::getErrorMessages([[self::KEY_LEVEL => self::TYPE_ERROR, self::KEY_MESSAGE => $value]]);
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    public static function errorsToStr(array $errors): string
    {
        $preparedErrors = [];
        self::collectValidatorMessages($errors, $preparedErrors);

        $rows = [];
        foreach ($preparedErrors as $field => $error) {
            $rows[] = "$field => $error";
        }

        return implode('; ', $rows);
    }

    /**
     * @inheritdoc
     */
    protected function stringify($objectOrArray): string
    {
        // create response object
        $responseObj = !empty($responseType = $this->responseType) ? new $responseType() : [];

        // response validation
        $errors = $this->dtService->transfer($objectOrArray, $responseObj);

        if (!empty($errors)) {
            // prepare validator errors
            $preparedErrors = [];
            self::collectValidatorMessages($errors, $preparedErrors);
            foreach ($preparedErrors as $field => $error) {
                $rows[] = [self::KEY_LEVEL => self::TYPE_ERROR, self::KEY_MESSAGE => "$field => $error"];
            }

            throw new HttpCode(500, 'Response validation failed', self::getErrorMessages($rows));
        }

        // result to array
        $result = [];
        $this->dtService->transfer($responseObj, $result);

        // get logger writers
        $loggerWriters = $this->logger->getWriters();

        // push logger messages
        if ($loggerWriters->count() > 0) {
            foreach ($loggerWriters->toArray() as $writer) {
                if ($writer instanceof Messages) {
                    foreach ($writer->getMessages() as $row) {
                        $result[self::KEY_MESSAGES][] = [
                            self::KEY_LEVEL => $row[Messages::KEY_LEVEL],
                            self::KEY_MESSAGE => $row[Messages::KEY_MESSAGE]
                        ];
                    }
                }
            }
        }

        return parent::stringify($result);
    }

    /**
     * @param string|array $data
     * @param array        $res
     * @param string       $name
     */
    protected static function collectValidatorMessages($data, array &$res, $name = '')
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (!empty($name)) {
                    $name .= '_';
                }
                $name .= $k;

                self::collectValidatorMessages($v, $res, $name);
            }
        }

        if (is_string($data)) {
            // parse name
            $name = explode("_", $name);
            array_pop($name);
            $name = array_pop($name);

            // push
            $res[$name] = $data;
        }
    }
}