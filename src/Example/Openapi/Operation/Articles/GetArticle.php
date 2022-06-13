<?php

declare(strict_types=1);

namespace Example\Openapi\Operation\Articles;

use Example\Openapi\OpenapiResponse;
use Example\Openapi\Parameter\GetArticleParameters;

interface GetArticle
{
    public function getArticle(GetArticleParameters $parameters): OpenapiResponse;
}
