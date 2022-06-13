<?php

declare(strict_types=1);

namespace Example\Openapi\Operation\Articles;

use Example\Openapi\OpenapiResponse;
use Example\Openapi\Parameter\CreateArticleParameters;
use Example\Openapi\Request\CreateArticleRequest;
use Example\Openapi\Schema\ArticlePostRequest;

interface CreateArticle
{
    public function perform(CreateArticleRequest $request): OpenapiResponse;
}
