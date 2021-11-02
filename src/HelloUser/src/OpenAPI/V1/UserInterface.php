<?php

declare(strict_types=1);

namespace HelloUser\OpenAPI\V1;

use HelloUser\OpenAPI\V1\DTO\User;
use HelloUser\OpenAPI\V1\DTO\UserResult;

interface UserInterface
{
    public function post(User $bodyData): UserResult;

    public function getById(string $id): UserResult;
}