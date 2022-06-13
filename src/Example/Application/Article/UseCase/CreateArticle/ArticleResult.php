<?php

declare(strict_types=1);

namespace Example\Application\Article\UseCase\CreateArticle;

use Example\Application\Structs\Article;
use Serializable;

interface ArticleResult extends Serializable
{
    public function getLastState(): State;
    public function getResult(): Article;
    public function getErrorInfo(): array;

    public function checkCurrentState(): State;
}
