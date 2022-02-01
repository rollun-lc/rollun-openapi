<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/DataStoreExample/v1' => [
                \DataStoreExample\OpenAPI\V1\Server\Handler\User::class,
                \DataStoreExample\OpenAPI\V1\Server\Handler\UserId::class,
            ],
        ],
    ],
    'dependencies' => [
        'invokables' => [
            \DataStoreExample\OpenAPI\V1\Server\Rest\User::class => \DataStoreExample\OpenAPI\V1\Server\Rest\User::class,
        ],
    ],
];