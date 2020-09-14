<?php

namespace HelloUser\OpenAPI\V1\Client\Rest;

use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class User
 */
class User extends BaseAbstract
{
	/** @var string */
	protected $apiName = '\HelloUser\OpenAPI\V1\Client\Api\UserApi';


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		// send request
		$data = $this->getApi()->userIdGet($id);

		// validation of response
		$result = $this->transfer((array)$data, \HelloUser\OpenAPI\V1\DTO\UserResult::class);

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
		$bodyDataObject = $this->transfer((array)$bodyData, '\HelloUser\OpenAPI\V1\DTO\User');

		// send request
		$data = $this->getApi()->userPost($bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \HelloUser\OpenAPI\V1\DTO\UserResult::class);

		return $result;
	}


	/**
	 * @return \HelloUser\OpenAPI\V1\Client\Api\UserApi
	 */
	protected function getApi(): object
	{
		return $this->api;
	}
}
