<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/DataStoreExample/v1' => [
                \DataStoreExample\OpenAPI\Server\V1\Handler\User::class,
                \DataStoreExample\OpenAPI\Server\V1\Handler\UserId::class,
            ],
        ],
    ],
    'dependencies'                                     => [
        'invokables' => [
            \DataStoreExample\OpenAPI\Server\V1\Rest\User::class => \DataStoreExample\OpenAPI\Server\V1\Rest\User::class,
        ],
    ],
    'dataStore'                                        => [
        'exampleUserDataStore' => [
            'class'     => \rollun\datastore\DataStore\CsvBase::class,
            'filename'  => 'data/example-user-datastore.csv',
            'delimiter' => ','
        ],
    ]
];