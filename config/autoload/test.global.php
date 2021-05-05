<?php

use rollun\test\OpenAPI\Unit\Openapi\ControllerObject;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

return [
    'dependencies' => [
        'invokables' => [
            ControllerObject::class
        ]
    ],
    /*AbstractServiceAbstractFactory::KEY => [
        ControllerObject::class => [
            AbstractServiceAbstractFactory::KEY_CLASS => ControllerObject::class,
            AbstractServiceAbstractFactory::KEY_DEPENDENCIES => []
        ]
    ]*/
];