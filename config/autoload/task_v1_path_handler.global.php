<?php

use rollun\datastore\DataStore\CsvBase;
use Task\OpenAPI\Server\V1\Adapter\FileSummaryRestAdapter;

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
            'FileSummary' => FileSummaryRestAdapter::class,
        ],
        'invokables' => [
            FileSummaryRestAdapter::class => FileSummaryRestAdapter::class,
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