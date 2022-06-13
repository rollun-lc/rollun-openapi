<?php

declare(strict_types=1);

namespace Example\Openapi\Parameter;

class CreateArticleParameters
{
    public function __construct(
        public readonly CreateArticleHeaders $headers
    )
    {
    }
}
