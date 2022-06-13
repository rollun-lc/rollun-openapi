<?php

declare(strict_types=1);

namespace Example\Application\Structs;

class Article
{
    public function __construct(
        public readonly string $id,
        public readonly string $title
    )
    {
    }
}
