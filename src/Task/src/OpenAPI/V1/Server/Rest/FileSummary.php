<?php

namespace Task\OpenAPI\V1\Server\Rest;

use OpenAPI\Server\Rest\Base7Abstract;
use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;

/**
 * Class FileSummary
 */
class FileSummary extends Base7Abstract
{
	public const CONTROLLER_OBJECT = 'FileSummary1Controller';

	/** @var object */
	protected $controllerObject;

	/** @var LoggerInterface */
	protected $logger;


	/**
	 * FileSummary constructor.
	 *
	 * @param mixed $controllerObject
	 * @param LoggerInterface|null logger
	 *
	 * @throws \ReflectionException
	 */
	public function __construct($controllerObject = null, $logger = null)
	{
		InsideConstruct::init(['controllerObject' => static::CONTROLLER_OBJECT, 'logger' => LoggerInterface::class]);
	}


	/**
	 * @inheritDoc
	 *
	 * @param \Task\OpenAPI\V1\DTO\PostFileSummary $bodyData
	 */
	public function post($bodyData = null)
	{
		if (method_exists($this->controllerObject, 'post')) {
		    $bodyDataArray = (array) $bodyData;

		    return $this->controllerObject->post($bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 */
	public function deleteById($id)
	{
		if (method_exists($this->controllerObject, 'deleteById')) {
		    return $this->controllerObject->deleteById($id);
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
