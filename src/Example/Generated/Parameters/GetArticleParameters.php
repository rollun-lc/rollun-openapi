<?php

declare(strict_types=1);

namespace Example\Generated\Parameters;

class GetArticleParameters
{
    public function __construct(
        public readonly GetArticlePathParameters $pathParameters
    )
    {
    }
}
