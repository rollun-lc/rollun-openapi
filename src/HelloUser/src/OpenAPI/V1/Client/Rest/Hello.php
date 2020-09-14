<?php

namespace HelloUser\OpenAPI\V1\Client\Rest;

use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class Hello
 */
class Hello extends BaseAbstract
{
	/** @var string */
	protected $apiName = '\HelloUser\OpenAPI\V1\Client\Api\HelloApi';


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		// send request
		$data = $this->api->helloIdGet($id);

		// validation of response
		$result = $this->transfer((array)$data, \HelloUser\OpenAPI\V1\DTO\HelloResult::class);

		return $result;
	}
}
