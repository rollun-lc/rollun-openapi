<?php

namespace Test\OpenAPI\V1_0_1\Client\Rest;

use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class Bla
 */
class Bla extends BaseAbstract
{
	public const API_NAME = '\Test\OpenAPI\V1_0_1\Client\Api\BlaApi';

	/**
	 * @inheritDoc
	 *
	 * @param array $queryData
	 */
	public function get($queryData = [])
	{
		// validation of $queryData
		if ($queryData instanceof \Test\OpenAPI\V1_0_1\DTO\BlaGETQueryData) {
		    $queryData = $this->toArray($queryData);
		}
		if (isset($queryData['id']) && is_array($queryData['id'])) {
		    $queryData['id'] = implode(',', $queryData['id']);
		}
		$queryDataObject = $this->transfer((array)$queryData, '\Test\OpenAPI\V1_0_1\DTO\BlaGETQueryData');


		// send request
		$data = $this->getApi()->blaGet($queryDataObject->name, $queryDataObject->id);

		// validation of response
		$result = $this->transfer((array)$data, \Test\OpenAPI\V1_0_1\DTO\BlaResult::class);

		return $result;
	}


	/**
	 * @return \Test\OpenAPI\V1_0_1\Client\Api\BlaApi
	 */
	protected function getApi(): \OpenAPI\Client\Api\ApiInterface
	{
		return $this->api;
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function post($bodyData = null)
	{
		// send request
		$result = $this->getApi()->blaPost();

		return $result;
	}
}
