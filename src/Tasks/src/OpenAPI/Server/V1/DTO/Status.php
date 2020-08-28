<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Status
{
    /**
     * Current state
     * @DTA\Data(field="state")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $state;
    /**
     * All possible states
     * @DTA\Data(field="allStates")
     * TODO check validator and strategy are correct and can handle container item type
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"Type", "options":{"type":"mixed"}}
     * }})
     * @var mixed[]
     */
    public $all_states;
}
