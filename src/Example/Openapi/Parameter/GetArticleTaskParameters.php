<?php

declare(strict_types=1);

namespace Example\Openapi\Parameter;

class GetArticleTaskParameters
{
    public function __construct(
        public readonly GetArticleTaskPathParameters $pathParameters
    )
    {
    }
}
