<?php

namespace Test\OpenAPI\V1_0_1\Server\Rest;

use OpenAPI\Server\Rest\Base7Abstract;
use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;

/**
 * Class Test
 */
class Test extends Base7Abstract
{
	public const CONTROLLER_OBJECT = 'Test1_0_1Controller';

	/** @var object */
	protected $controllerObject;

	/** @var LoggerInterface */
	protected $logger;


	/**
	 * Test constructor.
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
	 * @param \Test\OpenAPI\V1_0_1\DTO\TestGETQueryData $queryData
	 */
	public function get($queryData = [])
	{
		if (method_exists($this->controllerObject, 'get')) {
		    $queryDataArray = (array) $queryData;

		    return $this->controllerObject->get($queryDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 *
	 * @param \Test\OpenAPI\V1_0_1\DTO\Test $bodyData
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
	public function getById($id)
	{
		if (method_exists($this->controllerObject, 'getById')) {
		    return $this->controllerObject->getById($id);
		}

		throw new \Exception('Not implemented method');
	}
}
