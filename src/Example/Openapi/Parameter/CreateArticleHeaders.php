<?php

declare(strict_types=1);

namespace Example\Openapi\Parameter;

class CreateArticleHeaders
{
    public function __construct(
        public readonly string $contentLanguage
    )
    {
    }
}
