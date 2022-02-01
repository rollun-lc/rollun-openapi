<?php

namespace DataStoreExample\OpenAPI\V1\Server\Rest;

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


	/**
	 * User constructor.
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
	 * @param \DataStoreExample\OpenAPI\V1\DTO\UserDELETEQueryData $queryData
	 */
	public function delete($queryData = [])
	{
		if (method_exists($this->controllerObject, 'delete')) {
		    $queryDataArray = (array) $queryData;

		    return $this->controllerObject->delete($queryDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 *
	 * @param \DataStoreExample\OpenAPI\V1\DTO\UserGETQueryData $queryData
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
	 * @param \DataStoreExample\OpenAPI\V1\DTO\UserPATCHQueryData $queryData
	 * @param \DataStoreExample\OpenAPI\V1\DTO\User $bodyData
	 */
	public function patch($queryData, $bodyData)
	{
		if (method_exists($this->controllerObject, 'patch')) {
		    $bodyDataArray = (array) $bodyData;;
		    $queryDataArray = (array) $queryData;

		    return $this->controllerObject->patch($queryDataArray, $bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 *
	 * @param \DataStoreExample\OpenAPI\V1\DTO\PostUser[] $bodyData
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
	 * @inheritDoc
	 *
	 * @param \DataStoreExample\OpenAPI\V1\DTO\User $bodyData
	 */
	public function patchById($id, $bodyData)
	{
		if (method_exists($this->controllerObject, 'patchById')) {
		    $bodyDataArray = (array) $bodyData;

		    return $this->controllerObject->patchById($id, $bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 *
	 * @param \DataStoreExample\OpenAPI\V1\DTO\PutUser $bodyData
	 */
	public function putById($id, $bodyData)
	{
		if (method_exists($this->controllerObject, 'putById')) {
		    $bodyDataArray = (array) $bodyData;

		    return $this->controllerObject->putById($id, $bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}
}
