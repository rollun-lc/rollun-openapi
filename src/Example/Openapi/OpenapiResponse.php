<?php

declare(strict_types=1);

namespace Example\Openapi;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class OpenapiResponse
{
    public static function created($resource): self
    {
        return new self();
    }

    public static function accepted(): self
    {
        return new self();
    }

    public static function error(): self
    {
        return new self();
    }
}
