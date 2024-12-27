<?php
declare(strict_types=1);

namespace Test\OpenAPI\V1_0_1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * Query parameters for testPathParamCustomGET
 * @property string $queryParam
 * @property array $arrayParam
 */
class TestPathParamCustomGETQueryData implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @ODTA\Data(field="queryParam", required=false)
     * @DTA\Strategy(name="QueryParameter", options={"type":"string"})
     * @DTA\Validator(name="QueryParameterType", options={"type":"string"})
     * @var string
     */
    private string $queryParam;
    /**
     * @ODTA\Data(field="arrayParam", required=false)
     * @DTA\Strategy(name="QueryParameterArray", options={"type":"string", "format":"csv"})
     * @DTA\Validator(name="QueryParameterArrayType", options={"type":"string", "format":"csv"})
     * @var string[]
     */
    private array $arrayParam;

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
        return ['queryParam', 'arrayParam'];
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

    public function getArrayParam(): array
    {
        return $this->arrayParam;
    }

    public function setArrayParam(array $arrayParam): self
    {
        $this->arrayParam = $arrayParam;
        return $this;
    }

    public function hasArrayParam(): bool
    {
        return $this->isInitialized('arrayParam');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
