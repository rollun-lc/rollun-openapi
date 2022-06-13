<?php

declare(strict_types=1);

namespace Example\Application\Article\UseCase\CreateArticle;

enum State
{
    case PENDING;
    case FULFILLED;
    case REJECTED;
}
