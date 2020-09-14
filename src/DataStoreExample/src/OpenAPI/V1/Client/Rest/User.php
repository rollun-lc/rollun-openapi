<?php

namespace DataStoreExample\OpenAPI\V1\Client\Rest;

use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class User
 */
class User extends BaseAbstract
{
	/** @var string */
	protected $apiName = '\DataStoreExample\OpenAPI\V1\Client\Api\UserApi';


	/**
	 * @inheritDoc
	 *
	 * @param array $queryData
	 */
	public function delete($queryData = [])
	{
		// validation of $queryData
		$queryDataObject = $this->transfer((array)$queryData, \DataStoreExample\OpenAPI\V1\DTO\UserDELETEQueryData::class);

		// send request
		$data = $this->getApi()->userDelete($queryData['rql']);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\Result::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $queryData
	 */
	public function get($queryData = [])
	{
		// validation of $queryData
		$queryDataObject = $this->transfer((array)$queryData, \DataStoreExample\OpenAPI\V1\DTO\UserGETQueryData::class);

		// send request
		$data = $this->getApi()->userGet($queryData['rql'],$queryData['limit'],$queryData['offset'],$queryData['sort_by'],$queryData['sort_order']);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\UsersResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 */
	public function deleteById($id)
	{
		// send request
		$data = $this->getApi()->userIdDelete($id);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\Result::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		// send request
		$data = $this->getApi()->userIdGet($id);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\UserResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function patchById($id, $bodyData)
	{
		// validation of $bodyData
		$bodyDataObject = $this->transfer((array)$bodyData, 'string');

		// send request
		$data = $this->getApi()->userIdPatch($id, $bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\UserResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function putById($id, $bodyData)
	{
		// validation of $bodyData
		$bodyDataObject = $this->transfer((array)$bodyData, 'string');

		// send request
		$data = $this->getApi()->userIdPut($id, $bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\UserResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $queryData
	 * @param array $bodyData
	 */
	public function patch($queryData, $bodyData)
	{
		// validation of $queryData
		$queryDataObject = $this->transfer((array)$queryData, \DataStoreExample\OpenAPI\V1\DTO\UserPATCHQueryData::class);

		// validation of $bodyData
		$bodyDataObject = $this->transfer((array)$bodyData, '\DataStoreExample\OpenAPI\V1\DTO\User');

		// send request
		$data = $this->getApi()->userPatch($bodyData,$queryData['rql']);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\UsersResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function post($bodyData)
	{
		// validation of $bodyData
		$bodyDataObject = $this->transfer((array)$bodyData, '\DataStoreExample\OpenAPI\V1\DTO\PostUser[]');

		// send request
		$data = $this->getApi()->userPost($bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \DataStoreExample\OpenAPI\V1\DTO\UsersResult::class);

		return $result;
	}


	/**
	 * @return \DataStoreExample\OpenAPI\V1\Client\Api\UserApi
	 */
	protected function getApi(): object
	{
		return $this->api;
	}
}
