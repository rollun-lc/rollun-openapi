<?php
declare(strict_types=1);

namespace OpenAPI\Server\Producer;

use Articus\DataTransfer\Service as DTService;
use Articus\PathHandler\Exception\HttpCode;
use Articus\PathHandler\Producer\Transfer as Base;
use InvalidArgumentException;
use OpenAPI\Server\Response\Message;
use OpenAPI\Server\Response\MessageReaderInterface;
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
    const KEY_LEVEL = 'level';
    const KEY_MESSAGE = 'text';
    const KEY_TYPE = 'type';

    const LEVEL_ERROR = LogLevel::ERROR;

    const TYPE_UNDEFINED_ERROR = Message::UNDEFINED_TYPE;
    const TYPE_VALIDATION_ERROR = Message::UNDEFINED_TYPE;

    /**
     * @var string|null
     */
    protected $responseType;

    /**
     * @var MessageReaderInterface
     */
    private $messageReader;

    /**
     * Transfer constructor.
     *
     * @param callable $streamFactory
     * @param DTService $dtService
     * @param MessageReaderInterface $messageReader
     * @param string $subset
     * @param string|null $responseType
     */
    public function __construct(
        callable $streamFactory,
        DTService $dtService,
        MessageReaderInterface $messageReader,
        string $subset,
        ?string $responseType = null
    )
    {
        parent::__construct($streamFactory, $dtService, $subset);

        $this->responseType = $responseType;
        $this->messageReader = $messageReader;
    }

    /**
     * @param array $messages
     *
     * @return array
     */
    public static function getErrorMessages(array $messages): array
    {
        foreach ($messages as $row) {
            if (!isset($row[self::KEY_LEVEL]) || !isset($row[self::KEY_MESSAGE]) || !isset($row[self::KEY_TYPE])) {
                throw new InvalidArgumentException('Invalid message array');
            }
        }

        return [self::KEY_MESSAGES => $messages];
    }

    /**
     * @param string $value
     * @param string $type
     *
     * @return array
     */
    public static function getSingleErrorMessages(string $value, string $type = self::TYPE_UNDEFINED_ERROR): array
    {
        return self::getErrorMessages([
            [
                self::KEY_LEVEL => self::LEVEL_ERROR,
                self::KEY_MESSAGE => $value,
                self::KEY_TYPE => $type
            ]
        ]);
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
     * @throws HttpCode
     */
    protected function stringify($objectOrArray): string
    {
        // create response object
        $responseObj = !empty($responseType = $this->responseType) ? new $responseType() : [];

        // response validation
        $errors = $this->transferUnknownType($objectOrArray, $responseObj);

        if (!empty($errors)) {
            // prepare validator errors
            $preparedErrors = [];
            self::collectValidatorMessages($errors, $preparedErrors);
            foreach ($preparedErrors as $field => $error) {
                $rows[] = [
                    self::KEY_LEVEL => self::LEVEL_ERROR,
                    self::KEY_MESSAGE => "$field => $error",
                    self::KEY_TYPE => self::TYPE_VALIDATION_ERROR
                ];
            }

            throw new HttpCode(500, 'Response validation failed', self::getErrorMessages($rows));
        }

        // result to array
        $result = [];
        $this->transferUnknownType($responseObj, $result);

        foreach ($this->messageReader->read() as $message) {
            $result[self::KEY_MESSAGES][] = [
                self::KEY_LEVEL => $message->getLevel(),
                self::KEY_MESSAGE => $message->getText(),
                self::KEY_TYPE => $message->getType()
            ];
        }

        return parent::stringify($result);
    }

    /**
     * @param string|array $data
     * @param array $res
     * @param string $name
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


    /**
     * Since 3.0 version of articus/data-transfer we cannot call one transfer method in all cases.
     * So we need to determine from what to what we move data
     *
     * @param array|object $from
     * @param array|object $to
     * @return array list of violations found during data validation
     */
    protected function transferUnknownType($from, &$to): array
    {
        if (is_array($from)) {
            if (is_array($to)) {
                throw new InvalidArgumentException('Data transfer from array to array is not possible.');
            } else {
                return $this->dtService->transferToTypedData($from, $to);
            }
        }

        // TODO
        if (is_object($to)) {
            return $this->dtService->transferTypedData($from, $to);
        } else {
            $to = $this->transfer($from);
            return [];
        }
    }
}