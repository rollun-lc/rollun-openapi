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
}