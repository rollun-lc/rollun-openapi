<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

use Laminas\Stdlib\ArrayUtils\MergeRemoveKey;
use Psr\Log\LoggerInterface;

return [
    'log' => [
        LoggerInterface::class => [
            'writers' => [
                'stream_stdout' => new MergeRemoveKey()
            ],
        ],
    ],
];
