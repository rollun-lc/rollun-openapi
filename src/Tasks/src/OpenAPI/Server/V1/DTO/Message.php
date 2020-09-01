<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\DTO;

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
    public $level;
    /**
     * Message text
     * @DTA\Data(field="text")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $text;
    /**
     * Message context (like in a logger)
     * @DTA\Data(field="context", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Type", "options":{"type":"string"}}
     * }})
     * @var string[]
     */
    public $context;
}
