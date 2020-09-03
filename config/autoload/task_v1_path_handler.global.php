<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths' => [
            '/openapi/Task/v1' => [
                \Task\OpenAPI\Server\V1\Handler\FileSummary::class,
                \Task\OpenAPI\Server\V1\Handler\FileSummaryId::class,
            ],
        ],
    ]
];