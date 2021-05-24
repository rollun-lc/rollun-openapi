<?php

namespace OpenAPI\Server\Response;

class MessageCollector implements MessageReaderInterface, MessageWriterInterface
{
    /**
     * @var Message[]
     */
    private $messages = [];

    public function read(): array
    {
        return $this->messages;
    }

    public function write(string $level, string $text, ?string $type = null): void
    {
        $this->messages[] = new Message($level, $text, $type);
    }

    public function emergency(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::EMERGENCY, $text, $type);
    }

    public function alert(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::ALERT, $text, $type);
    }

    public function critical(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::CRITICAL, $text, $type);
    }

    public function error(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::ERROR, $text, $type);
    }

    public function warning(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::WARNING, $text, $type);
    }

    public function notice(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::NOTICE, $text, $type);
    }

    public function info(string $text, ?string $type = null): void
    {
        $this->write(MessageWriterInterface::INFO, $text, $type);
    }
}