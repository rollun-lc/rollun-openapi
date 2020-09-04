<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/HelloUser/v1' => [
                \HelloUser\OpenAPI\Server\V1\Handler\HelloId::class,
                \HelloUser\OpenAPI\Server\V1\Handler\User::class,
                \HelloUser\OpenAPI\Server\V1\Handler\UserId::class,
            ],
        ],
    ],
    'dependencies'                                     => [
        'invokables' => [
            \HelloUser\OpenAPI\Server\V1\Rest\Hello::class => \HelloUser\OpenAPI\Server\V1\Rest\Hello::class,
            \HelloUser\OpenAPI\Server\V1\Rest\User::class  => \HelloUser\OpenAPI\Server\V1\Rest\User::class,
        ],
    ],
];