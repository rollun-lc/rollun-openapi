<?php

use OpenAPI\Client\Factory\ApiAbstractFactory;
use OpenAPI\Client\Factory\RestAbstractFactory;
use rollun\test\OpenAPI\functional\Openapi\BlaControllerObject;
use rollun\test\OpenAPI\functional\Openapi\TestControllerObject;
use Test\OpenAPI\V1_0_1\Client\Api\TestApi;
use Test\OpenAPI\V1_0_1\Client\Rest\Test;

return [
    'dependencies' => [
        'invokables' => [
            TestControllerObject::class,
            BlaControllerObject::class,
        ],
        'aliases' => [
            'Bla1_0_1Controller' => BlaControllerObject::class,
            'Test1_0_1Controller' => TestControllerObject::class,
        ]
    ],
    RestAbstractFactory::KEY => [
        'TestClient1' => [
            RestAbstractFactory::KEY_CLASS => Test::class,
            RestAbstractFactory::KEY_API_NAME => 'TestClient1Api',
        ]
    ],
    ApiAbstractFactory::KEY => [
        'TestClient1Api' => [
            ApiAbstractFactory::KEY_CLASS => TestApi::class,
            ApiAbstractFactory::KEY_HOST_INDEX => 1
        ]
    ]
];