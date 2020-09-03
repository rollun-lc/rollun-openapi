<?php
declare(strict_types=1);

namespace Task\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class SummaryResult
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * @DTA\Strategy(name="Object", options={"type":\Task\OpenAPI\Server\V1\DTO\Summary::class})
     * @DTA\Validator(name="Dictionary", options={"type":\Task\OpenAPI\Server\V1\DTO\Summary::class})
     * @var \Task\OpenAPI\Server\V1\DTO\Summary
     */
    public $data;
    /**
     * @DTA\Data(field="messages", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\Task\OpenAPI\Server\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Dictionary", "options":{"type":\Task\OpenAPI\Server\V1\DTO\Message::class}}
     * }})
     * @var \Task\OpenAPI\Server\V1\DTO\Message[]
     */
    public $messages;
}
