<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/Task/v1' => [
                \Task\OpenAPI\V1\Server\Handler\FileSummary::class,
                \Task\OpenAPI\V1\Server\Handler\FileSummaryId::class,
            ],
        ],
    ],
    'dependencies'                                     => [
        'invokables' => [
            \rollun\Callables\TaskExample\FileSummary::class => \rollun\Callables\TaskExample\FileSummary::class,
            \Task\OpenAPI\V1\Server\Rest\FileSummary::class  => \Task\OpenAPI\V1\Server\Rest\FileSummary::class,
        ],
    ],
];