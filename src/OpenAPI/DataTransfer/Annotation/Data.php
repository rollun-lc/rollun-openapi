<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Annotation;

/**
 * Annotation for declaring class field that should be hydrated and extracted
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Data extends \Articus\DataTransfer\Annotation\Data
{
    /**
     * Flag if field value in array is required
     * @var bool
     */
    public $required = true;
}