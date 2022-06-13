<?php

declare(strict_types=1);

namespace Example\Openapi\Parameter;

class GetArticleParameters
{
    public function __construct(
        public readonly GetArticlePathParameters $pathParameters
    )
    {
    }
}
