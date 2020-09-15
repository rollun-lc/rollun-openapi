<?php
declare(strict_types=1);

namespace Task\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Stage
{
    /**
     * Current stage
     * @DTA\Data(field="stage")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $stage;
    /**
     * All possible stages
     * @DTA\Data(field="all")
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Type", "options":{"type":"string"}}
     * }})
     * @var string[]
     */
    public $all;
}
