<?php

declare(strict_types=1);

namespace Example\Openapi\Client\Articles\CreateArticle;

use Example\Application\Article\UseCase\CreateArticle\ArticleResult;
use Example\Application\Article\UseCase\CreateArticle\Command;
use Example\Openapi\OpenapiResponse;

interface Serializer
{
    public function serialize(Command $command): array;

    public function deserialize(OpenapiResponse $response): ArticleResult;
}
