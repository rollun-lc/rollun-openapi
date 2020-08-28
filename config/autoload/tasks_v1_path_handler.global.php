<?php

return [
    \Articus\PathHandler\RouteInjection\Factory::class => [
        'paths'    => [
            '/openapi/Tasks/v1/FileSummary' => [
                \Tasks\OpenAPI\Server\V1\Handler\FileSummary\Task::class,
                \Tasks\OpenAPI\Server\V1\Handler\FileSummary\TaskId::class,
            ],
        ],
        'handlers' => [
            'invokables' => [
                \Tasks\OpenAPI\Server\V1\Handler\FileSummary\Task::class   => \Tasks\OpenAPI\Server\V1\Handler\FileSummary\Task::class,
                \Tasks\OpenAPI\Server\V1\Handler\FileSummary\TaskId::class => \Tasks\OpenAPI\Server\V1\Handler\FileSummary\TaskId::class,
            ]
        ]
    ]
];