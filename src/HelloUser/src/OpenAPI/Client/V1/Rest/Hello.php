<?php

namespace HelloUser\OpenAPI\Client\V1\Rest;

use GuzzleHttp\Client;
use HelloUser\OpenAPI\Client\V1\Api\HelloApi;
use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;

/**
 * Class Hello
 */
class Hello extends BaseAbstract
{
	const IS_API_CLIENT = true;

	/** @var HelloApi */
	protected $api;


	/**
	 * Hello constructor.
	 *
	 * @param string|null $lifeCycleToken
	 */
	public function __construct($lifeCycleToken)
	{
		$this->api = new HelloApi(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id): ResultInterface
	{
		$result = $this->api->helloIdGet($id);

		return new Result($result['data'], $result['messages']);
	}
}
