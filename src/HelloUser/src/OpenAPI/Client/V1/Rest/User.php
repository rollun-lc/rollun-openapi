<?php

namespace HelloUser\OpenAPI\Client\V1\Rest;

use GuzzleHttp\Client;
use HelloUser\OpenAPI\Client\V1\Api\UserApi;
use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;

/**
 * Class User
 */
class User extends BaseAbstract
{
	const IS_API_CLIENT = true;

	/** @var UserApi */
	protected $api;


	/**
	 * User constructor.
	 *
	 * @param string|null $lifeCycleToken
	 */
	public function __construct($lifeCycleToken)
	{
		$this->api = new UserApi(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id): ResultInterface
	{
		$result = $this->api->userIdGet($id);

		return new Result($result['data'], $result['messages']);
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function post($bodyData): ResultInterface
	{
		$result = $this->api->userPost(new \HelloUser\OpenAPI\Client\V1\Model\InlineObject($bodyData));

		return new Result($result['data'], $result['messages']);
	}
}
