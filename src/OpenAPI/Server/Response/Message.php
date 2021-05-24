<?php

namespace OpenAPI\Server\Response;

use InvalidArgumentException;
use Psr\Log\LogLevel;

class Message
{
    public const UNDEFINED_TYPE = 'UNDEFINED';
    public const INVALID_RESPONSE_TYPE = 'INVALID_RESPONSE';

    public const DEFAULT_TYPE = self::UNDEFINED_TYPE;

    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $level, string $text, ?string $type = null)
    {
        if (!in_array($level, self::getLevels())) {
            throw new InvalidArgumentException('Level must be on of [' . implode(', ', self::getLevels()) . "] not '$level'.");
        }

        $this->level = $level;
        $this->text = $text;
        $this->type = is_null($type) ? self::DEFAULT_TYPE : $type;
    }

    private static function getLevels(): array
    {
        return [
            LogLevel::DEBUG,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
            LogLevel::ERROR,
            LogLevel::CRITICAL,
            LogLevel::ALERT,
            LogLevel::EMERGENCY
        ];
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}