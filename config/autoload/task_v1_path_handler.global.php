<?php

use rollun\datastore\DataStore\CsvBase;
use Task\OpenAPI\Server\V1\Rest\FileSummary;

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/Task/v1' => [
                \Task\OpenAPI\Server\V1\Handler\FileSummary::class,
                \Task\OpenAPI\Server\V1\Handler\FileSummaryId::class,
            ],
        ],
    ],
    'dependencies'                                     => [
        'aliases'    => [
            'FileSummary' => FileSummary::class,
        ],
        'invokables' => [
            FileSummary::class => FileSummary::class,
        ],
    ],
    'dataStore'                                        => [
        'exampleDataStore' => [
            'class'     => CsvBase::class,
            'filename'  => 'data/example-datastore.csv',
            'delimiter' => ','
        ],
    ]
];