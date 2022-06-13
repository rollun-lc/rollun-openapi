<?php

declare(strict_types=1);

namespace Example\Implementation\Server\Application\Article\UseCase\CreateArticle;

use Example\Application\Article\UseCase\CreateArticle\ArticleResult;
use Example\Application\Article\UseCase\CreateArticle\Command;

class Handler implements \Example\Application\Article\UseCase\CreateArticle\Handler
{
    public function handle(Command $command): ArticleResult
    {
    }
}
