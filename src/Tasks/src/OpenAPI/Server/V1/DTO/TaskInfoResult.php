<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class TaskInfoResult
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * @DTA\Strategy(name="Object", options={"type":\Tasks\OpenAPI\Server\V1\DTO\TaskInfo::class})
     * @DTA\Validator(name="Dictionary", options={"type":\Tasks\OpenAPI\Server\V1\DTO\TaskInfo::class})
     * @var \Tasks\OpenAPI\Server\V1\DTO\TaskInfo
     */
    public $data;
    /**
     * @DTA\Data(field="messages", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\Tasks\OpenAPI\Server\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Dictionary", "options":{"type":\Tasks\OpenAPI\Server\V1\DTO\Message::class}}
     * }})
     * @var \Tasks\OpenAPI\Server\V1\DTO\Message[]
     */
    public $messages;
}
