<?php

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
        'invokables' => [
            \Task\OpenAPI\Server\V1\Rest\FileSummary::class  => \Task\OpenAPI\Server\V1\Rest\FileSummary::class,
            \rollun\Callables\TaskExample\FileSummary::class => \rollun\Callables\TaskExample\FileSummary::class,
        ],
    ],
];