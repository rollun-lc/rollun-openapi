<?php

declare(strict_types=1);

namespace Example\Openapi\Parameter;

class GetArticlePathParameters
{
    public function __construct(
        public readonly string $id
    )
    {
    }
}
