<?php

declare(strict_types=1);

namespace rollun\test\OpenAPI\functional\DataTransfer\EmptyValues;

use ArrayObject;
use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;

/**
 * @property $id
 * @property $snakeCase
 * @property $camelCase
 */
class User extends ArrayObject
{
    /**
     * @ODTA\Data(field="id")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private $id;

    /**
     * @ODTA\Data(field="snake_case", nullable=false, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private $snakeCase;

    /**
     * @ODTA\Data(field="camelCase", nullable=false, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private $camelCase;

    public function __get($name)
    {
        if (array_key_exists($name, (array)$this)) {
            return $this[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this[$name] = $value;
    }

    public function setId($id)
    {
        $this['id'] = $id;
    }

    public function getId()
    {
        return $this['id'];
    }

    public function setSnakeCase($name)
    {
        $this['snakeCase'] = $name;
    }

    public function getSnakeCase()
    {
        return $this['snakeCase'];
    }

    public function setCamelCase($name)
    {
        $this['camelCase'] = $name;
    }

    public function getCamelCase()
    {
        return $this['camelCase'];
    }

    public function hasId(): bool
    {
        return array_key_exists('id', (array)$this);
    }

    public function hasSnakeCase(): bool
    {
        return array_key_exists('snakeCase', (array)$this);
    }

    public function hasCamelCase(): bool
    {
        return array_key_exists('camelCase', (array)$this);
    }
}
