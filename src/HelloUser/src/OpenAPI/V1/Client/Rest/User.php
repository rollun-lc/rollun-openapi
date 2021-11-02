<?php

namespace HelloUser\OpenAPI\V1\Client\Rest;

use HelloUser\OpenAPI\V1\DTO\UserResult;
use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class User
 */
class User extends BaseAbstract implements UserInterface
{
	public const CONFIGURATION_CLASS = 'HelloUser\OpenAPI\V1\Client\Configuration';

	/** @var string */
	protected $apiName = '\HelloUser\OpenAPI\V1\Client\Api\UserApi';


	/**
	 * @return \HelloUser\OpenAPI\V1\Client\Api\UserApi
	 */
	protected function getApi(): \OpenAPI\Client\Api\ApiInterface
	{
		return $this->api;
	}

    public function post(\HelloUser\OpenAPI\V1\DTO\User $bodyData): UserResult
    {
        // validation of $bodyData
        $bodyDataObject = $this->transfer((array)$bodyData, '\HelloUser\OpenAPI\V1\DTO\User');

        // send request
        $data = $this->getApi()->userPost($bodyData);

        // validation of response
        $result = $this->transfer((array)$data, \HelloUser\OpenAPI\V1\DTO\UserResult::class);

        return $result;
    }

    public function getById(string $id): UserResult
    {
        // send request
        $data = $this->getApi()->userIdGet($id);

        // validation of response
        $result = $this->transfer((array)$data, \HelloUser\OpenAPI\V1\DTO\UserResult::class);

        return $result;
    }
}
