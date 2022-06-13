<?php

declare(strict_types=1);

namespace Example\Implementation\Client;

class Article
{
    public function __construct(
        public readonly string $id,
        public readonly string $title
    )
    {
    }
}
