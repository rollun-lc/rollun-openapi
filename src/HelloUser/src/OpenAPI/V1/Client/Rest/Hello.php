<?php

namespace HelloUser\OpenAPI\V1\Client\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use GuzzleHttp\Client;
use HelloUser\OpenAPI\V1\Client\Api\HelloApi;
use OpenAPI\Server\Rest\BaseAbstract;
use rollun\dic\InsideConstruct;

/**
 * Class Hello
 */
class Hello extends BaseAbstract
{
	const IS_API_CLIENT = true;

	/** @var HelloApi */
	protected $api;

	/** @var DataTransferService */
	protected $dt;


	/**
	 * Hello constructor.
	 *
	 * @param string|null $lifeCycleToken
	 * @param DataTransferService|null $dt
	 */
	public function __construct($lifeCycleToken, $dt = null)
	{
		$this->api = new HelloApi(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));
		InsideConstruct::init(['dt' => DataTransferService::class]);
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		$data = $this->api->helloIdGet($id);
		$result = new \HelloUser\OpenAPI\V1\DTO\HelloResult();

		$errors = $this->dt->transfer($data, $result);
		if (!empty($errors)) {
		    throw new \Exception('Validation of response is failed! Details: '. json_encode($errors));
		}

		return $result;
	}
}
