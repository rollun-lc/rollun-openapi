<?php

declare(strict_types=1);

namespace Example\Openapi\Request;

use Example\Openapi\Parameter\CreateArticleParameters;
use Example\Openapi\RequestBody\ArticlesPostRequestBody;

class CreateArticleRequest
{
    public function __construct(
        private readonly CreateArticleParameters $parameters,
        private readonly ArticlesPostRequestBody $requestBody
    )
    {
    }

    /**
     * @return CreateArticleParameters
     */
    public function getParameters(): CreateArticleParameters
    {
        return $this->parameters;
    }

    /**
     * @return ArticlesPostRequestBody
     */
    public function getRequestBody(): ArticlesPostRequestBody
    {
        return $this->requestBody;
    }
}
