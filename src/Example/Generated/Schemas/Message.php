<?php
declare(strict_types=1);

namespace Example\Generated\Schemas;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property string $level
 * @property string $type
 * @property string $text
 */
class Message implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @ODTA\Data(field="level", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @DTA\Validator(name="Enum", options={"allowed":{
     *      "'emergency'",
     *      "'alert'",
     *      "'critical'",
     *      "'error'",
     *      "'warning'",
     *      "'notice'",
     *      "'info'"
     * }})
     * @var string
     */
    private string $level;
    /**
     * You can expose this enum for all your errors UNDEFINED - Any undefined message type LOGGER_MESSAGE - Same as undefined
     * @ODTA\Data(field="type", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @DTA\Validator(name="Enum", options={"allowed":{
     *      "'UNDEFINED'",
     *      "'LOGGER_MESSAGE'"
     * }})
     * @var string
     */
    private string $type;
    /**
     * Message, that describes what went wrong
     * @ODTA\Data(field="text", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $text;

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
        return ['level', 'type', 'text'];
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function hasLevel(): bool
    {
        return $this->isInitialized('level');
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function hasType(): bool
    {
        return $this->isInitialized('type');
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function hasText(): bool
    {
        return $this->isInitialized('text');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
