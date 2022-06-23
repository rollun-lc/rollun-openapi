<?php

namespace Test\OpenAPI\V1_0_1\Server\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use OpenAPI\Server\Rest\Base7Abstract;
use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;

/**
 * Class Bla
 */
class Bla extends Base7Abstract
{
	public const CONTROLLER_OBJECT = 'Bla1_0_1Controller';

	/** @var object */
	protected $controllerObject;

	/** @var LoggerInterface */
	protected $logger;

	/** @var DataTransferService */
	protected $dataTransfer;


	/**
	 * Bla constructor.
	 *
	 * @param mixed $controllerObject
	 * @param LoggerInterface|null logger
	 * @param DataTransferService|null dataTransfer
	 *
	 * @throws \ReflectionException
	 */
	public function __construct($controllerObject = null, $logger = null, $dataTransfer = null)
	{
		InsideConstruct::init([
		    'controllerObject' => static::CONTROLLER_OBJECT,
		    'logger' => LoggerInterface::class,
		    'dataTransfer' => DataTransferService::class
		]);
	}


	/**
	 * @inheritDoc
	 *
	 * @param \Test\OpenAPI\V1_0_1\DTO\BlaGETQueryData $queryData
	 */
	public function get($queryData = [])
	{
		if (method_exists($this->controllerObject, 'get')) {
		    $queryDataArray =$this->dataTransfer->extractFromTypedData($queryData);

		    return $this->controllerObject->get($queryDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 *
	 * @param  $bodyData
	 */
	public function post($bodyData = null)
	{
		if (method_exists($this->controllerObject, 'post')) {
		    $bodyDataArray =$this->dataTransfer->extractFromTypedData($bodyData);

		    return $this->controllerObject->post($bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}
}
