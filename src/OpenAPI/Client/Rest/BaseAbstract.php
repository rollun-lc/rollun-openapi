<?php
declare(strict_types=1);

namespace OpenAPI\Client\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use OpenAPI\Client\Api\ApiInterface;
use Psr\Log\LoggerInterface;

/**
 * Abstract class BaseAbstract
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
abstract class BaseAbstract extends \OpenAPI\Server\Rest\BaseAbstract implements ClientInterface
{
    const IS_API_CLIENT = true;

    /**
     * @var string
     */
    protected $apiName = '';

    /**
     * @var ApiInterface
     */
    protected $api;

    /**
     * @var DataTransferService
     */
    protected $dataTransfer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * BaseAbstract constructor.
     *
     * @param DataTransferService $dataTransfer
     * @param LoggerInterface     $logger
     * @param string              $lifeCycleToken
     */
    public function __construct(DataTransferService $dataTransfer, LoggerInterface $logger, string $lifeCycleToken)
    {
        // prepare api name
        $apiName = $this->apiName;
        if (empty($this->apiName)) {
            throw new InvalidArgumentException('Param $apiName is required!');
        }

        $this->dataTransfer = $dataTransfer;
        $this->logger = $logger;
        $this->api = $this->createApi($apiName, $lifeCycleToken);
    }

    /**
     * @param string $apiName
     * @param string $lifeCycleToken
     *
     * @return ApiInterface
     */
    protected function createApi(string $apiName, string $lifeCycleToken): ApiInterface
    {
        $api = new $apiName(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));

        if (method_exists($api, 'setLogger')) {
            $api->setLogger($this->logger);
        }

        return $api;
    }

    /**
     * @param array       $data
     * @param string      $dto
     * @param string|null $validationMessage
     *
     * @return object|array
     * @throws Exception
     */
    protected function transfer(array $data, string $dto, string $validationMessage = null)
    {
        // prepare validation message
        if ($validationMessage === null) {
            $validationMessage = 'Validation is failed!';
        }

        if (substr($dto, -2) == '[]') {
            // prepare class name
            $dto = str_replace('[]', '', $dto);

            $result = [];
            $errors = [];
            foreach ($data as $item) {
                $object = new $dto();
                $errors = array_merge($errors, $this->dataTransfer->transfer($item, $object));
                $result[] = $object;
            }
        } else {
            $result = new $dto();
            $errors = $this->dataTransfer->transfer($data, $result);
        }

        if (!empty($errors)) {
            throw new Exception($validationMessage . ' Details: ' . json_encode($errors));
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function setHostIndex(int $index): void
    {
        $this->api->setHostIndex($index);
    }

    /**
     * {@inheritDoc}
     */
    public function getHosts(): array
    {
        return $this->api->getHosts();
    }
}
