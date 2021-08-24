<?php
declare(strict_types=1);

namespace Test\OpenAPI\V1_0_1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class TestCustomResponse
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * @DTA\Strategy(name="Object", options={"type":\Test\OpenAPI\V1_0_1\DTO\TestCustom::class})
     * @DTA\Validator(name="TypeCompliant", options={"type":\Test\OpenAPI\V1_0_1\DTO\TestCustom::class})
     * @var \Test\OpenAPI\V1_0_1\DTO\TestCustom
     */
    public $data;
    /**
     * @DTA\Data(field="messages", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\Test\OpenAPI\V1_0_1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\Test\OpenAPI\V1_0_1\DTO\Message::class}}
     * }})
     * @var \Test\OpenAPI\V1_0_1\DTO\Message[]
     */
    public $messages;
}
