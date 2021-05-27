<?php

use rollun\test\OpenAPI\Unit\Openapi\ControllerObject;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

return [
    'dependencies' => [
        'invokables' => [
            \rollun\test\OpenAPI\Unit\Openapi\TestControllerObject::class,
            \rollun\test\OpenAPI\Unit\Openapi\BlaControllerObject::class,
        ],
        'aliases' => [
            'BlaController' => \rollun\test\OpenAPI\Unit\Openapi\BlaControllerObject::class,
            'TestController' => \rollun\test\OpenAPI\Unit\Openapi\TestControllerObject::class,
        ]
    ],
    /*AbstractServiceAbstractFactory::KEY => [
        ControllerObject::class => [
            AbstractServiceAbstractFactory::KEY_CLASS => ControllerObject::class,
            AbstractServiceAbstractFactory::KEY_DEPENDENCIES => []
        ]
    ]*/
];