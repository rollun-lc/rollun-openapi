<?php

namespace OpenAPI\Server\Response;

use InvalidArgumentException;
use Psr\Log\LogLevel;

class Message
{
    // Levels
    public const EMERGENCY = LogLevel::EMERGENCY;
    public const ALERT = LogLevel::ALERT;
    public const CRITICAL = LogLevel::CRITICAL;
    public const ERROR = LogLevel::ERROR;
    public const WARNING = LogLevel::WARNING;
    public const NOTICE = LogLevel::NOTICE;
    public const INFO = LogLevel::INFO;
    
    // Types
    public const UNDEFINED_TYPE = 'UNDEFINED';
    public const INVALID_RESPONSE_TYPE = 'INVALID_RESPONSE';
    public const REQUEST_TIMEOUT = 'REQUEST_TIMEOUT';
    public const SERVICE_UNAVAILABLE = 'SERVICE_UNAVAILABLE';

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
            static::INFO,
            static::NOTICE,
            static::WARNING,
            static::ERROR,
            static::CRITICAL,
            static::ALERT,
            static::EMERGENCY
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