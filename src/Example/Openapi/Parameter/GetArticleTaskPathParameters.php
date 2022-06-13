<?php

declare(strict_types=1);

namespace Example\Openapi\Parameter;

class GetArticleTaskPathParameters
{
    public function __construct(
        public readonly string $id
    )
    {
    }
}
