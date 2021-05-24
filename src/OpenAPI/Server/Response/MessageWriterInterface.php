<?php

namespace OpenAPI\Server\Response;

interface MessageWriterInterface
{
    /**
     * Write message to response.
     *
     * @param string $level PSR-3 Logger levels
     * @param string $text Human readable message
     * @param string|null $type Special constant that is specified in the manifest. 'UNDEFINED' by default
     * @return mixed
     */
    public function write(string $level, string $text, ?string $type = null): void;
}