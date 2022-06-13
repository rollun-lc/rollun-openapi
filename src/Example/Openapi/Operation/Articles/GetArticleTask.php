<?php

declare(strict_types=1);

namespace Example\Openapi\Operation\Articles;

use Example\Openapi\OpenapiResponse;
use Example\Openapi\Parameter\CreateArticleParameters;
use Example\Openapi\Parameter\GetArticleParameters;
use Example\Openapi\Parameter\GetArticleTaskParameters;
use Example\Openapi\Schema\ArticlePostRequest;

interface GetArticleTask
{
    public function getArticleTask(GetArticleTaskParameters $parameters): OpenapiResponse;
}
