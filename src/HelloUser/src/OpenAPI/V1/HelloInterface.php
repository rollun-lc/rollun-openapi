<?php

declare(strict_types=1);

namespace HelloUser\OpenAPI\V1;

use HelloUser\OpenAPI\V1\DTO\HelloResult;

interface HelloInterface
{
    public function getById(string $id): HelloResult;
}