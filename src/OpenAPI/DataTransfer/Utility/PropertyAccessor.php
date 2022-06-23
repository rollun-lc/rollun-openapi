<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Utility;

/**
 * Provides single interface to access object properties using metadata declarations.
 */
class PropertyAccessor extends \Articus\DataTransfer\Utility\PropertyAccessor
{
    /**
     * @param string|null $hasser name of has method
     * @param bool $default default value to return if there is no hasser
     * @return bool
     */
    public function has(?string $hasser = null, bool $default = false): bool
    {
        if (is_null($hasser)) {
            return $default;
        }

        return $this->object->{$hasser}();
    }
}
