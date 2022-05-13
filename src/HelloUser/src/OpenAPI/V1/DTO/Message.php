<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Message
{
    /**
     * Message level  (like in a logger)
     * @DTA\Data(field="level")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public string $level;
    /**
     * Message text
     * @DTA\Data(field="text")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public string $text;
    /**
     * Message context (like in a logger)
     * @DTA\Data(field="context", nullable=true)
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Type", "options":{"type":"string"}}
     * }})
     * @var string[]
     */
    public array $context;
}
