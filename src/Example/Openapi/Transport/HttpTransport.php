<?php

declare(strict_types=1);

namespace Example\Openapi\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpTransport
{
    public function handle(RequestInterface $request): ResponseInterface;
}
