<?php
declare(strict_types=1);

namespace Test\OpenAPI\V1_0_1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property string $pathParam
 * @property string $queryParam
 */
class TestCustom implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @ODTA\Data(field="pathParam", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $pathParam;
    /**
     * @ODTA\Data(field="queryParam", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $queryParam;

    public function &__get($name)
    {
        if ($this->isInitialized($name)) {
            return $this->{$name};
        }
        $null = null;
        return $null;
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

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->toArray());
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $result = [];
        foreach (self::getAllPropertyNames() as $propertyName) {
            if ($this->isInitialized($propertyName)) {
                $result[$propertyName] = $this->{$propertyName};
            }
        }
        return $result;
    }

    private static function getAllPropertyNames(): array
    {
        return ['pathParam', 'queryParam'];
    }

    public function getPathParam(): string
    {
        return $this->pathParam;
    }

    public function setPathParam(string $pathParam): self
    {
        $this->pathParam = $pathParam;
        return $this;
    }

    public function hasPathParam(): bool
    {
        return $this->isInitialized('pathParam');
    }

    public function getQueryParam(): string
    {
        return $this->queryParam;
    }

    public function setQueryParam(string $queryParam): self
    {
        $this->queryParam = $queryParam;
        return $this;
    }

    public function hasQueryParam(): bool
    {
        return $this->isInitialized('queryParam');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
