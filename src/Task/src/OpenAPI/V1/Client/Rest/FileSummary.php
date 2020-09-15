<?php

namespace Task\OpenAPI\V1\Client\Rest;

use OpenAPI\Client\Rest\BaseAbstract;

/**
 * Class FileSummary
 */
class FileSummary extends BaseAbstract
{
	/** @var string */
	protected $apiName = '\Task\OpenAPI\V1\Client\Api\FileSummaryApi';


	/**
	 * @inheritDoc
	 */
	public function deleteById($id)
	{
		// send request
		$data = $this->getApi()->fileSummaryIdDelete($id);

		// validation of response
		$result = $this->transfer((array)$data, \Task\OpenAPI\V1\DTO\DeleteResult::class);

		return $result;
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		// send request
		$data = $this->getApi()->fileSummaryIdGet($id);

		// validation of response
		$result = $this->transfer((array)$data, \Task\OpenAPI\V1\DTO\TaskInfoResult::class);

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
		$bodyDataObject = $this->transfer((array)$bodyData, '\Task\OpenAPI\V1\DTO\PostFileSummary');

		// send request
		$data = $this->getApi()->fileSummaryPost($bodyData);

		// validation of response
		$result = $this->transfer((array)$data, \Task\OpenAPI\V1\DTO\TaskInfoResult::class);

		return $result;
	}


	/**
	 * @return \Task\OpenAPI\V1\Client\Api\FileSummaryApi
	 */
	protected function getApi(): object
	{
		return $this->api;
	}
}
