<?php

declare(strict_types=1);

namespace Example\Application\Article\UseCase\CreateArticle;

interface Handler
{
    public function handle(Command $command): ArticleResult;
}
