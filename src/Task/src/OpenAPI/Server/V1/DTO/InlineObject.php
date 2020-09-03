<?php
declare(strict_types=1);

namespace Task\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class InlineObject
{
    /**
     * N parameter
     * @DTA\Data(field="n", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"int"})
     * @var int
     */
    public $n;
}
