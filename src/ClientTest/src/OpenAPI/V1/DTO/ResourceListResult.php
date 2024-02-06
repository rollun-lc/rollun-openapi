<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class ResourceListResult
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\ClientTest\OpenAPI\V1\DTO\Resource::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\ClientTest\OpenAPI\V1\DTO\Resource::class}}
     * }})
     * @var \ClientTest\OpenAPI\V1\DTO\Resource[]
     */
    public $data;
    /**
     * @DTA\Data(field="messages", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\ClientTest\OpenAPI\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\ClientTest\OpenAPI\V1\DTO\Message::class}}
     * }})
     * @var \ClientTest\OpenAPI\V1\DTO\Message[]
     */
    public $messages;
}
