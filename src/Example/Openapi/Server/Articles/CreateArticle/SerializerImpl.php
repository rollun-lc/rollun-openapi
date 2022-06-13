<?php

declare(strict_types=1);

namespace Example\Openapi\Server\Articles\CreateArticle;

use Example\Application\Article\UseCase\CreateArticle\ArticleResult;
use Example\Application\Article\UseCase\CreateArticle\Command;
use Example\Application\Article\UseCase\CreateArticle\State;
use Example\Application\Structs\Article;
use Example\Openapi\OpenapiResponse;
use Example\Openapi\Parameter\CreateArticleParameters;
use Example\Openapi\Schema\ArticlePostRequest;

class SerializerImpl implements Serializer
{
    public function deserialize(CreateArticleParameters $parameters, ArticlePostRequest $requestBody): Command
    {
        return new Command($requestBody->title, $parameters->headers->contentLanguage);
    }

    public function serialize(ArticleResult $result): OpenapiResponse
    {
        return match ($result->getLastState()) {
            State::FULFILLED => OpenapiResponse::created($this->createResource($result->getResult())),
            State::PENDING => OpenapiResponse::accepted(),
            State::REJECTED => OpenapiResponse::error()
        };
    }

    private function createResource(Article $article): mixed
    {
        return $article;
    }
}
