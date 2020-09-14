<?php

namespace HelloUser\OpenAPI\V1\Client\Rest;

use OpenAPI\Server\Rest\Client\BaseAbstract;

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
		$data = $this->api->userIdGet($id);

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
		$bodyDataObject = $this->transfer((array)$bodyData, \HelloUser\OpenAPI\V1\DTO\User::class);

		// send request
		$data = $this->api->userPost($bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \HelloUser\OpenAPI\V1\DTO\UserResult::class);

		return $result;
	}
}
