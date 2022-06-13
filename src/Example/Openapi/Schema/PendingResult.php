<?php
declare(strict_types=1);

namespace Example\Openapi\Schema;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;
use Traversable;

/**
 * @property \Articles\OpenAPI\V1\DTO\Article $data
 * @property array $messages
 */
class PendingResult implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @ODTA\Data(field="data", required=false)
     * @DTA\Strategy(name="Object", options={"type":\Articles\OpenAPI\V1\DTO\Article::class})
     * @DTA\Validator(name="TypeCompliant", options={"type":\Articles\OpenAPI\V1\DTO\Article::class})
     * @var \Articles\OpenAPI\V1\DTO\Article
     */
    private \Articles\OpenAPI\V1\DTO\Article $data;
    /**
     * @ODTA\Data(field="messages", required=false)
     * @DTA\Strategy(name="ObjectArray", options={"type":\Articles\OpenAPI\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\Articles\OpenAPI\V1\DTO\Message::class}}
     * }})
     * @var \Articles\OpenAPI\V1\DTO\Message[]
     */
    private array $messages;

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
        return ['data', 'messages'];
    }

    public function getData(): \Articles\OpenAPI\V1\DTO\Article
    {
        return $this->data;
    }

    public function setData(\Articles\OpenAPI\V1\DTO\Article $data): self
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
