<?php
declare(strict_types=1);

namespace Test\OpenAPI\V1_0_1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 * Query parameters for testGET
 */
class TestGETQueryData
{
    /**
     * @DTA\Data(field="name", nullable=true)
     * @DTA\Strategy(name="QueryParameter", options={"type":"string"})
     * @DTA\Validator(name="QueryParameterType", options={"type":"string"})
     * @var string
     */
    public $name;
}
