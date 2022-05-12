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
     */
    public bool $required = true;

    /**
     * Name of the method that allows to check property exists.
     * If null and property ("has" . <property name in camel case>) is used.
     * If empty string, there is no way to check property exists.
     */
    public string|null $hasser = null;
}
