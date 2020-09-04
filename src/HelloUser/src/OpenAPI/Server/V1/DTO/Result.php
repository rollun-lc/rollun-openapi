<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\Server\V1\DTO;

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
     * @DTA\Strategy(name="ObjectArray", options={"type":\HelloUser\OpenAPI\Server\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Dictionary", "options":{"type":\HelloUser\OpenAPI\Server\V1\DTO\Message::class}}
     * }})
     * @var \HelloUser\OpenAPI\Server\V1\DTO\Message[]
     */
    public $messages;
}
