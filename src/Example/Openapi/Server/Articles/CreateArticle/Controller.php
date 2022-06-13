<?php

declare(strict_types=1);

namespace Example\Openapi\Server\Articles\CreateArticle;

use Example\Application\Article\UseCase\CreateArticle\Handler;
use Example\Openapi\OpenapiResponse;
use Example\Openapi\Operation\Articles\CreateArticle;
use Example\Openapi\Parameter\CreateArticleParameters;
use Example\Openapi\Schema\ArticlePostRequest;

class Controller implements CreateArticle
{
    public function __construct(
        private readonly Handler $createArticleHandler,
        private readonly Serializer $serializer
    )
    {
    }

    public function perform(CreateArticleParameters $parameters, ArticlePostRequest $requestBody): OpenapiResponse
    {
        $command = $this->serializer->deserialize($parameters, $requestBody);
        $result = $this->createArticleHandler->handle($command);
        return $this->serializer->serialize($result);
    }
}
