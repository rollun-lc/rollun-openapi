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


	/**
	 * @param $pathParam
	 * @param \Test\OpenAPI\V1_0_1\DTO\TestPathParamCustomGETQueryData $queryData
	 */
	public function testPathParamCustomGet($pathParam, \Test\OpenAPI\V1_0_1\DTO\TestPathParamCustomGETQueryData $queryData)
	{
		if (method_exists($this->controllerObject, 'testPathParamCustomGet')) {
		    return $this->controllerObject->testPathParamCustomGet($pathParam, $queryData);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @param $pathParam
	 * @param \Test\OpenAPI\V1_0_1\DTO\Test $bodyData
	 */
	public function testPathParamCustomPost($pathParam, \Test\OpenAPI\V1_0_1\DTO\Test $bodyData)
	{
		if (method_exists($this->controllerObject, 'testPathParamCustomPost')) {
		    return $this->controllerObject->testPathParamCustomPost($pathParam, $bodyData);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @param $pathParam
	 * @param \Test\OpenAPI\V1_0_1\DTO\CustomOperationGetQueryData $queryData
	 */
	public function customOperationGet($pathParam, \Test\OpenAPI\V1_0_1\DTO\CustomOperationGetQueryData $queryData)
	{
		if (method_exists($this->controllerObject, 'customOperationGet')) {
		    return $this->controllerObject->customOperationGet($pathParam, $queryData);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @param $pathParam
	 * @param \Test\OpenAPI\V1_0_1\DTO\Test $bodyData
	 */
	public function customOperationPost($pathParam, \Test\OpenAPI\V1_0_1\DTO\Test $bodyData)
	{
		if (method_exists($this->controllerObject, 'customOperationPost')) {
		    return $this->controllerObject->customOperationPost($pathParam, $bodyData);
		}

		throw new \Exception('Not implemented method');
	}
}
