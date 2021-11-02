<?php

use HelloUser\Hello\Controller\V1\HelloController;
use HelloUser\OpenAPI\V1\Server\Rest\Hello as RestHello;
use HelloUser\OpenAPI\V1\Server\Rest\HelloInterface;
use HelloUser\OpenAPI\V1\Server\Rest\User as RestUser;
use HelloUser\OpenAPI\V1\Server\Rest\UserInterface;
use HelloUser\User\Controller\V1\UserController;

return [
    'dependencies' => [
        'aliases' => [
            RestUser::CONTROLLER_OBJECT => UserController::class,
            RestHello::CONTROLLER_OBJECT => HelloController::class,
            HelloInterface::class => HelloController::class,
            UserInterface::class => UserController::class
        ]
    ]
];