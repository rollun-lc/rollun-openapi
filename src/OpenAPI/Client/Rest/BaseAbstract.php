<?php
declare(strict_types=1);

namespace OpenAPI\Client\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

/**
 * Abstract class BaseAbstract
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
abstract class BaseAbstract extends \OpenAPI\Server\Rest\BaseAbstract
{
    const IS_API_CLIENT = true;

    /**
     * @var string
     */
    protected $apiName = '';

    /**
     * @var object
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
     * @param mixed               $lifeCycleToken
     */
    public function __construct(DataTransferService $dataTransfer, LoggerInterface $logger, $lifeCycleToken)
    {
        // prepare api name
        $apiName = $this->apiName;
        if (empty($this->apiName)) {
            throw new \InvalidArgumentException('Param $apiName is required!');
        }

        $this->api = $this->createApi($apiName, $lifeCycleToken);
        $this->dataTransfer = $dataTransfer;
        $this->logger = $logger;
    }

    /**
     * @param string $apiName
     * @param mixed  $lifeCycleToken
     *
     * @return object
     */
    protected function createApi(string $apiName, $lifeCycleToken): object
    {
        return new $apiName(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));
    }

    /**
     * @param array       $data
     * @param string      $dto
     * @param string|null $validationMessage
     *
     * @return object
     * @throws \Exception
     */
    protected function transfer(array $data, string $dto, string $validationMessage = null): object
    {
        // prepare validation message
        if ($validationMessage === null) {
            $validationMessage = 'Validation is failed!';
        }

        $object = new $dto();
        $errors = $this->dataTransfer->transfer($data, $object);
        if (!empty($errors)) {
            throw new \Exception($validationMessage . ' Details: ' . json_encode($errors));
        }

        return $object;
    }
}
