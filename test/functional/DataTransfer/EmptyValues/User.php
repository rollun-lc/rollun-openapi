<?php

declare(strict_types=1);

namespace rollun\test\OpenAPI\functional\DataTransfer\EmptyValues;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;

/**
 * @property $id
 * @property $snakeCase
 * @property $camelCase
 */
class User
{
    /**
     * @ODTA\Data(field="id")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $id;

    /**
     * @ODTA\Data(field="snake_case", nullable=false, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $snakeCase;

    /**
     * @ODTA\Data(field="camelCase", nullable=false, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $camelCase;

    public function __get($name)
    {
        return $this->isInitialized($name) ? $this->{$name} : null;
    }

    public function __set(string $name, $value): void
    {
        $this->{$name} = $value;
    }

    public function __isset(string $name): bool
    {
        return $this->isInitialized($name) && isset($this->{$name});
    }

    public function __unset(string $name): void
    {
        unset($this->{$name});
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSnakeCase(): string
    {
        return $this->snakeCase;
    }

    public function setSnakeCase($value): void
    {
        $this->snakeCase = $value;
    }

    public function getCamelCase(): string
    {
        return $this->camelCase;
    }

    public function setCamelCase($value): void
    {
        $this->camelCase = $value;
    }

    public function hasId(): bool
    {
        return $this->isInitialized('id');
    }

    public function hasSnakeCase(): bool
    {
        return $this->isInitialized('snakeCase');
    }

    public function hasCamelCase(): bool
    {
        return $this->isInitialized('camelCase');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
