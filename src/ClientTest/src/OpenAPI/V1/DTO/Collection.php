<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property array $inner
 */
class Collection implements \IteratorAggregate, \JsonSerializable
{
    /**
     * List of ordered products
     * @ODTA\Data(field="inner", required=false)
     * @DTA\Strategy(name="ObjectArray", options={"type":\ClientTest\OpenAPI\V1\DTO\NestedObject::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\ClientTest\OpenAPI\V1\DTO\NestedObject::class}}
     * }})
     * @var \ClientTest\OpenAPI\V1\DTO\NestedObject[]
     */
    private array $inner;

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
        return ['inner'];
    }

    public function getInner(): array
    {
        return $this->inner;
    }

    public function setInner(array $inner): self
    {
        $this->inner = $inner;
        return $this;
    }

    public function hasInner(): bool
    {
        return $this->isInitialized('inner');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
