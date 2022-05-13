<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;
use ReflectionProperty;

/**
 * @property string $level
 * @property string $text
 * @property string $type
 */
class Message
{
    /**
     * Message level  (like in a logger)
     * @ODTA\Data(field="level")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $level;
    /**
     * Message text
     * @ODTA\Data(field="text")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private string $text;
    /**
     * @ODTA\Data(field="type", required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @DTA\Validator(name="Enum", options={"allowed":{
     *      "'UNDEFINED'"
     * }})
     * @var string
     */
    private string $type;

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

    public function setLevel(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function hasLevel(): bool
    {
        return $this->isInitialized('level');
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

    private function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(self::class, $property);
        $rp->setAccessible(true);
        return $rp->isInitialized($this);
    }
}
