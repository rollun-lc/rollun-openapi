<?php

namespace HelloUser\OpenAPI\V1\Client\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use GuzzleHttp\Client;
use HelloUser\OpenAPI\V1\Client\Api\UserApi;
use OpenAPI\Server\Rest\BaseAbstract;
use rollun\dic\InsideConstruct;

/**
 * Class User
 */
class User extends BaseAbstract
{
	const IS_API_CLIENT = true;

	/** @var UserApi */
	protected $api;

	/** @var DataTransferService */
	protected $dt;


	/**
	 * User constructor.
	 *
	 * @param string|null $lifeCycleToken
	 * @param DataTransferService|null $dt
	 */
	public function __construct($lifeCycleToken, $dt = null)
	{
		$this->api = new UserApi(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));
		InsideConstruct::init(['dt' => DataTransferService::class]);
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		$data = $this->api->userIdGet($id);
		$result = new \HelloUser\OpenAPI\V1\DTO\UserResult();

		$errors = $this->dt->transfer($data, $result);
		if (!empty($errors)) {
		    throw new \Exception('Validation of response is failed! Details: '. json_encode($errors));
		}

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function post($bodyData)
	{
		$errors = $this->dt->transfer($bodyData, new \HelloUser\OpenAPI\V1\DTO\User());
		if (!empty($errors)) {
		    throw new \Exception('Validation of request is failed! Details: '. json_encode($errors));
		}

		$data = $this->api->userPost($bodyData);
		$result = new \HelloUser\OpenAPI\V1\DTO\UserResult();

		$errors = $this->dt->transfer($data, $result);
		if (!empty($errors)) {
		    throw new \Exception('Validation of response is failed! Details: '. json_encode($errors));
		}

		return $result;
	}
}
