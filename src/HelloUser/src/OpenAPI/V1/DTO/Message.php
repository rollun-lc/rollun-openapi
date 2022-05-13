<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;

/**
 * @property string $level
 * @property string $text
 * @property array $context
 */
class Message
{
    /**
     * Message level  (like in a logger)
     * @DTA\Data(field="level")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $level;
    /**
     * Message text
     * @DTA\Data(field="text")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $text;
    /**
     * Message context (like in a logger)
     * @DTA\Data(field="context", required=false)
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Type", "options":{"type":"string"}}
     * }})
     * @var string[]
     */
    private array $context;

    public function __get($name)
    {
        return $this->isInitialized($name) ? $this->{$name} : null;
    }

    public function __set(string $name, $value): void
    {
        $this->{$name} = $value;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setLevel(string $level): void
    {
        $this->level = $level ;
    }

    public function hasLevel(): bool
    {
        return $this->isInitialized('level');
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text ;
    }

    public function hasText(): bool
    {
        return $this->isInitialized('text');
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): void
    {
        $this->context = $context ;
    }

    public function hasContext(): bool
    {
        return $this->isInitialized('context');
    }

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
