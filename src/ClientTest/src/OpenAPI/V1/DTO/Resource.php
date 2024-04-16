<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property string $id
 * @property string $requiredField
 * @property ?string $optionalField
 * @property \ClientTest\OpenAPI\V1\DTO\NestedObject $objectField
 * @property array $arrayField
 */
class Resource implements \IteratorAggregate, \JsonSerializable
{
    /**
     * Unique identificator of order (generated by server).
     * @ODTA\Data(field="id", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $id;
    /**
     * Order number received from supplier
     * @ODTA\Data(field="requiredField", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $requiredField;
    /**
     * @ODTA\Data(field="optionalField", nullable=true, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private ?string $optionalField;
    /**
     * @ODTA\Data(field="objectField", required=false)
     * @DTA\Strategy(name="Object", options={"type":\ClientTest\OpenAPI\V1\DTO\NestedObject::class})
     * @DTA\Validator(name="TypeCompliant", options={"type":\ClientTest\OpenAPI\V1\DTO\NestedObject::class})
     * @var \ClientTest\OpenAPI\V1\DTO\NestedObject
     */
    private \ClientTest\OpenAPI\V1\DTO\NestedObject $objectField;
    /**
     * List of ordered products
     * @ODTA\Data(field="arrayField", required=false)
     * @DTA\Strategy(name="ObjectArray", options={"type":\ClientTest\OpenAPI\V1\DTO\NestedObject::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\ClientTest\OpenAPI\V1\DTO\NestedObject::class}}
     * }})
     * @var \ClientTest\OpenAPI\V1\DTO\NestedObject[]
     */
    private array $arrayField;

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
        return ['id', 'requiredField', 'optionalField', 'objectField', 'arrayField'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function hasId(): bool
    {
        return $this->isInitialized('id');
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

    public function getObjectField(): \ClientTest\OpenAPI\V1\DTO\NestedObject
    {
        return $this->objectField;
    }

    public function setObjectField(\ClientTest\OpenAPI\V1\DTO\NestedObject $objectField): self
    {
        $this->objectField = $objectField;
        return $this;
    }

    public function hasObjectField(): bool
    {
        return $this->isInitialized('objectField');
    }

    public function getArrayField(): array
    {
        return $this->arrayField;
    }

    public function setArrayField(array $arrayField): self
    {
        $this->arrayField = $arrayField;
        return $this;
    }

    public function hasArrayField(): bool
    {
        return $this->isInitialized('arrayField');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
