<?php

declare(strict_types=1);

namespace Example\Generated\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Http
{
    public function send(RequestInterface $request): ResponseInterface;
}
