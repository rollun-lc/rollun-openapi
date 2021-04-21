<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/HelloUser/v1' => [
                \HelloUser\OpenAPI\V1\Server\Handler\HelloId::class,
                \HelloUser\OpenAPI\V1\Server\Handler\User::class,
                \HelloUser\OpenAPI\V1\Server\Handler\UserId::class,
            ],
        ],
    ],
    'dependencies' => [
        'invokables' => [
            \HelloUser\OpenAPI\V1\Server\Rest\User::class => \HelloUser\OpenAPI\V1\Server\Rest\User::class,
            \HelloUser\OpenAPI\V1\Server\Rest\Hello::class => \HelloUser\OpenAPI\V1\Server\Rest\Hello::class,
        ],
    ],
];