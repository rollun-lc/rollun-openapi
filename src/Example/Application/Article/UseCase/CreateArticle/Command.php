<?php

declare(strict_types=1);

namespace Example\Application\Article\UseCase\CreateArticle;

class Command
{
    public function __construct(
        public readonly string $title,
        public readonly string $language
    )
    {
    }
}
