<?php
declare(strict_types=1);

namespace Test\OpenAPI\V1_0_1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class BlaCollection
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\Test\OpenAPI\V1_0_1\DTO\Bla::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\Test\OpenAPI\V1_0_1\DTO\Bla::class}}
     * }})
     * @var \Test\OpenAPI\V1_0_1\DTO\Bla[]
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
