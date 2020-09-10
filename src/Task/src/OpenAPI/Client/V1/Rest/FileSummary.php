<?php

namespace Task\OpenAPI\Client\V1\Rest;

use GuzzleHttp\Client;
use OpenAPI\Server\Rest\BaseAbstract;
use Task\OpenAPI\Client\V1\Api\FileSummaryApi;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;

/**
 * Class FileSummary
 */
class FileSummary extends BaseAbstract
{
	const IS_API_CLIENT = true;

	/** @var FileSummaryApi */
	protected $api;


	/**
	 * FileSummary constructor.
	 *
	 * @param string|null $lifeCycleToken
	 */
	public function __construct($lifeCycleToken)
	{
		$this->api = new FileSummaryApi(new Client(['headers' => ['LifeCycleToken' => $lifeCycleToken]]));
	}


	/**
	 * @inheritDoc
	 */
	public function deleteById($id): ResultInterface
	{
		$result = $this->api->fileSummaryIdDelete($id);

		return new Result($result['data'], $result['messages']);
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id): ResultInterface
	{
		$result = $this->api->fileSummaryIdGet($id);

		return new Result($result['data'], $result['messages']);
	}


	/**
	 * @inheritDoc
	 *
	 * @param array $bodyData
	 */
	public function post($bodyData): ResultInterface
	{
		$result = $this->api->fileSummaryPost(new \Task\OpenAPI\Client\V1\Model\PostFileSummary($bodyData));

		return new Result($result['data'], $result['messages']);
	}
}
