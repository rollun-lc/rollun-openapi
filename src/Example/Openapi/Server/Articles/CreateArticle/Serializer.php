<?php

declare(strict_types=1);

namespace Example\Openapi\Server\Articles\CreateArticle;

use Example\Application\Article\UseCase\CreateArticle\ArticleResult;
use Example\Application\Article\UseCase\CreateArticle\Command;
use Example\Openapi\OpenapiResponse;
use Example\Openapi\Parameter\CreateArticleParameters;
use Example\Openapi\Schema\ArticlePostRequest;

interface Serializer
{
    public function deserialize(CreateArticleParameters $parameters, ArticlePostRequest $requestBody): Command;

    public function serialize(ArticleResult $result): OpenapiResponse;
}
