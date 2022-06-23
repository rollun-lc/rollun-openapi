<?php

namespace HelloUser\OpenAPI\V1\Server\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use OpenAPI\Server\Rest\Base7Abstract;
use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;

/**
 * Class User
 */
class User extends Base7Abstract
{
	public const CONTROLLER_OBJECT = 'User1Controller';

	/** @var object */
	protected $controllerObject;

	/** @var LoggerInterface */
	protected $logger;

	/** @var DataTransferService */
	protected $dataTransfer;


	/**
	 * User constructor.
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
	 * @param \HelloUser\OpenAPI\V1\DTO\User $bodyData
	 */
	public function post($bodyData = null)
	{
		if (method_exists($this->controllerObject, 'post')) {
		    $bodyDataArray =$this->dataTransfer->extractFromTypedData($bodyData);

		    return $this->controllerObject->post($bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		if (method_exists($this->controllerObject, 'getById')) {
		    return $this->controllerObject->getById($id);
		}

		throw new \Exception('Not implemented method');
	}
}
