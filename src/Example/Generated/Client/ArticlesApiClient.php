<?php

declare(strict_types=1);

namespace Example\Generated\Client;

use Example\Generated\Parameters\CreateArticleParameters;
use Example\Generated\Parameters\GetArticleParameters;
use Example\Generated\Parameters\GetArticleTaskParameters;
use Example\Generated\Schemas\ArticlePostRequest;

interface ArticlesApiClient
{
    public function createArticle(CreateArticleParameters $parameters, ArticlePostRequest $requestBody);

    public function getArticle(GetArticleParameters $parameters);

    public function getArticleTask(GetArticleTaskParameters $parameters);
}
