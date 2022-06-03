<?php

declare(strict_types=1);

namespace Example\Generated\Parameters;

class GetArticleTaskParameters
{
    public function __construct(
        public readonly GetArticleTaskPathParameters $pathParameters
    )
    {
    }
}
