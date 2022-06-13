<?php

declare(strict_types=1);

namespace Example\Implementation\Client;

use Example\Application\Article\UseCase\CreateArticle\Command;
use Example\Application\Article\UseCase\CreateArticle\Handler;

class OpenapiArticleRepository implements ArticleRepository
{
    public function __construct(
        private readonly Handler $handler
    )
    {
    }

    public function createArticle(string $title, string $language): Article
    {
        $result = $this->handler->handle(new Command($title, $language));
    }
}
