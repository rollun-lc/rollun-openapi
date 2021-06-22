<?php
declare(strict_types=1);

namespace Test\OpenAPI\V1_0_1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Collection
{
    /**
     * @DTA\Data(field="data", nullable=true)
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Strategy(name="ObjectArray", options={"type":\Test\OpenAPI\V1_0_1\DTO\Test::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\Test\OpenAPI\V1_0_1\DTO\Test::class}}
     * }})
     * @var \Test\OpenAPI\V1_0_1\DTO\Test[]
     */
    public $data;
}
