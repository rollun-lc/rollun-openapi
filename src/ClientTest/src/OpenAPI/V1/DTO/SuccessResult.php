<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property object $data
 * @property array $messages
 */
class SuccessResult implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @ODTA\Data(field="data", required=false)
     * @DTA\Validator(name="Type", options={"type":"object"})
     * @var object
     */
    private object $data;
    /**
     * @ODTA\Data(field="messages", required=false)
     * @DTA\Strategy(name="ObjectArray", options={"type":\ClientTest\OpenAPI\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\ClientTest\OpenAPI\V1\DTO\Message::class}}
     * }})
     * @var \ClientTest\OpenAPI\V1\DTO\Message[]
     */
    private array $messages;

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
        return ['data', 'messages'];
    }

    public function getData(): object
    {
        return $this->data;
    }

    public function setData(object $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function hasData(): bool
    {
        return $this->isInitialized('data');
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    public function hasMessages(): bool
    {
        return $this->isInitialized('messages');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
