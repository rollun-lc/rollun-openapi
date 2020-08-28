<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Summary
{
    /**
     * Summary
     * @DTA\Data(field="summary")
     * @DTA\Validator(name="Type", options={"type":"int"})
     * @var int
     */
    public $summary;
}
