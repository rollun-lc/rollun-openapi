<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property string $requiredField
 * @property ?string $optionalField
 */
class ResourcePostRequest implements \IteratorAggregate, \JsonSerializable
{
    /**
     * required
     * @ODTA\Data(field="requiredField")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $requiredField;
    /**
     * optional
     * @ODTA\Data(field="optionalField", nullable=true, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private ?string $optionalField;

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
        return ['requiredField', 'optionalField'];
    }

    public function getRequiredField(): string
    {
        return $this->requiredField;
    }

    public function setRequiredField(string $requiredField): self
    {
        $this->requiredField = $requiredField;
        return $this;
    }

    public function hasRequiredField(): bool
    {
        return $this->isInitialized('requiredField');
    }

    public function getOptionalField(): ?string
    {
        return $this->optionalField;
    }

    public function setOptionalField(?string $optionalField): self
    {
        $this->optionalField = $optionalField;
        return $this;
    }

    public function hasOptionalField(): bool
    {
        return $this->isInitialized('optionalField');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
