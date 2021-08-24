<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/Test/v1_0_1' => [
                \Test\OpenAPI\V1_0_1\Server\Handler\Bla::class,
                \Test\OpenAPI\V1_0_1\Server\Handler\Test::class,
                \Test\OpenAPI\V1_0_1\Server\Handler\TestId::class,
                \Test\OpenAPI\V1_0_1\Server\Handler\TestPathParamCustom::class,
            ],
        ],
    ],
    'dependencies' => [
        'invokables' => [
            \Test\OpenAPI\V1_0_1\Server\Rest\Test::class => \Test\OpenAPI\V1_0_1\Server\Rest\Test::class,
            \Test\OpenAPI\V1_0_1\Server\Rest\Bla::class => \Test\OpenAPI\V1_0_1\Server\Rest\Bla::class,
        ],
    ],
];