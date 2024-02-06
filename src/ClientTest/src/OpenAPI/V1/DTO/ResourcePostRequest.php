<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class ResourcePostRequest
{
    /**
     * required
     * @DTA\Data(field="requiredField")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $requiredField;
    /**
     * optional
     * @DTA\Data(field="optionalField", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $optionalField;
}
