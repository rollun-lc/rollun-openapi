<?php

namespace ClientTest\OpenAPI\V1\Client\Rest;

use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class Resource
 */
class Resource extends BaseAbstract
{
	public const API_NAME = '\ClientTest\OpenAPI\V1\Client\Api\ResourceApi';

	/**
	 * @inheritDoc
	 *
	 * @param array $queryData
	 */
	public function get($queryData = [])
	{
		// validation of $queryData
		if ($queryData instanceof \ClientTest\OpenAPI\V1\DTO\ResourceGETQueryData) {
		    $queryData = $this->toArray($queryData);
		}
		$queryDataObject = $this->transfer((array)$queryData, '\ClientTest\OpenAPI\V1\DTO\ResourceGETQueryData');


		// send request
		$data = $this->getApi()->resourceGet($queryDataObject->filter);

		// validation of response
		$result = $this->transfer((array)$data, \ClientTest\OpenAPI\V1\DTO\ResourceListResult::class);

		return $result;
	}


	/**
	 * @return \ClientTest\OpenAPI\V1\Client\Api\ResourceApi
	 */
	protected function getApi(): \OpenAPI\Client\Api\ApiInterface
	{
		return $this->api;
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		// send request
		$data = $this->getApi()->resourceIdGet($id);

		// validation of response
		$result = $this->transfer((array)$data, \ClientTest\OpenAPI\V1\DTO\ResourceResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function post($bodyData = null)
	{
		// validation of $bodyData
		if ($bodyData instanceof \ClientTest\OpenAPI\V1\DTO\ResourcePostRequest) {
		    $bodyData = $this->toArray($bodyData);
		}
		$bodyDataObject = $this->transfer((array)$bodyData, '\ClientTest\OpenAPI\V1\DTO\ResourcePostRequest');


		// send request
		$data = $this->getApi()->resourcePost($bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \ClientTest\OpenAPI\V1\DTO\ResourceResult::class);

		return $result;
	}
}
