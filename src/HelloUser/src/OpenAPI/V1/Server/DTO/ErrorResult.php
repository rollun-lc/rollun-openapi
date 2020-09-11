<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\V1\Server\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class ErrorResult
{
    /**
     * @DTA\Data(field="messages", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\HelloUser\OpenAPI\V1\Server\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Dictionary", "options":{"type":\HelloUser\OpenAPI\V1\Server\DTO\Message::class}}
     * }})
     * @var \HelloUser\OpenAPI\V1\Server\DTO\Message[]
     */
    public $messages;
}
