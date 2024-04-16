<?php

namespace rollun\test\OpenAPI\functional;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

trait GuzzleMockTrait
{
    /**
     * @var Client|null
     */
    private $guzzleClient = null;

    /**
     * @var MockHandler|null
     */
    private $guzzleMockHandler = null;

    /**
     * @var callable|null
     */
    private $guzzleHistory = null;

    /**
     * @var array
     */
    private $guzzleHistoryContainer = [];

    protected function setUpGuzzleMock(): void
    {
        $this->guzzleClient = null;
        $this->guzzleMockHandler = null;
        $this->guzzleHistoryContainer = [];
    }

    protected function getGuzzleClient(): Client
    {
        if ($this->guzzleClient === null) {
            $handlerStack = HandlerStack::create($this->getGuzzleMockHandler());
            $handlerStack->push($this->getGuzzleHistory());
            $this->guzzleClient = new Client([
                'handler' => $handlerStack
            ]);
        }
        return $this->guzzleClient;
    }

    protected function getGuzzleMockHandler(): MockHandler
    {
        if ($this->guzzleMockHandler === null) {
            $this->guzzleMockHandler = new MockHandler();
        }
        return $this->guzzleMockHandler;
    }

    protected function getGuzzleHistory(): callable
    {
        if ($this->guzzleHistory === null) {
            $this->guzzleHistoryContainer = [];
            $this->guzzleHistory = Middleware::history($this->guzzleHistoryContainer);
        }
        return $this->guzzleHistory;
    }

    protected function createUri(string $path, array $params): UriInterface
    {
        $uri = new Uri($path);
        return $uri->withQuery(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
    }
}