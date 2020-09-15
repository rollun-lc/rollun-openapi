<?php
declare(strict_types=1);

namespace Task\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Result
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"object"})
     * @var object
     */
    public $data;
    /**
     * @DTA\Data(field="messages", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\Task\OpenAPI\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Dictionary", "options":{"type":\Task\OpenAPI\V1\DTO\Message::class}}
     * }})
     * @var \Task\OpenAPI\V1\DTO\Message[]
     */
    public $messages;
}
