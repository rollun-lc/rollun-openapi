<?php

declare(strict_types=1);

namespace Example\Implementation\Client;

interface ArticleRepository
{
    public function createArticle(string $title, string $language): Article;
}
