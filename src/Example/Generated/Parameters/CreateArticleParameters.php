<?php

declare(strict_types=1);

namespace Example\Generated\Parameters;

class CreateArticleParameters
{
    public function __construct(
        public readonly CreateArticleHeaders $headers
    )
    {
    }
}
