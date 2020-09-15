<?php
declare(strict_types=1);

namespace Task\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Summary
{
    /**
     * @DTA\Data(field="summary")
     * @DTA\Validator(name="Type", options={"type":"int"})
     * @var int
     */
    public $summary;
}
