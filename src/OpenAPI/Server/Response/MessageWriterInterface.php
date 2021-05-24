<?php

namespace OpenAPI\Server\Response;

interface MessageWriterInterface
{
    public const EMERGENCY = Message::EMERGENCY;
    public const ALERT = Message::ALERT;
    public const CRITICAL = Message::CRITICAL;
    public const ERROR = Message::ERROR;
    public const WARNING = Message::WARNING;
    public const NOTICE = Message::NOTICE;
    public const INFO = Message::INFO;

    /**
     * Write message to response.
     *
     * @param string $level PSR-3 Logger levels
     * @param string $text Human readable message
     * @param string|null $type Special constant that is specified in the manifest. 'UNDEFINED' by default
     * @return mixed
     */
    public function write(string $level, string $text, ?string $type = null): void;

    // Shortcuts

    public function emergency(string $text, ?string $type = null): void;

    public function alert(string $text, ?string $type = null): void;

    public function critical(string $text, ?string $type = null): void;

    public function error(string $text, ?string $type = null): void;

    public function warning(string $text, ?string $type = null): void;

    public function notice(string $text, ?string $type = null): void;

    public function info(string $text, ?string $type = null): void;
}