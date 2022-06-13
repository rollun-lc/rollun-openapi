<?php

declare(strict_types=1);

namespace Example\Openapi\Client\Articles\CreateArticle;

use Example\Application\Article\UseCase\CreateArticle\ArticleResult;
use Example\Application\Article\UseCase\CreateArticle\Command;
use Example\Openapi\Operation\Articles\CreateArticle;

class Handler implements \Example\Application\Article\UseCase\CreateArticle\Handler
{
    public function __construct(
        private readonly CreateArticle $operation,
        private readonly Serializer $serializer
    )
    {
    }

    public function handle(Command $command): ArticleResult
    {
        [$parameters, $requestBody] = $this->serializer->serialize($command);
        $response = $this->operation->perform($parameters, $requestBody);
        return $this->serializer->deserialize($response);
    }
}
