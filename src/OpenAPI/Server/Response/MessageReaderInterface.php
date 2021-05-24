<?php

namespace OpenAPI\Server\Response;

interface MessageReaderInterface
{
    /**
     * @return Message[]
     */
    public function read(): array;
}