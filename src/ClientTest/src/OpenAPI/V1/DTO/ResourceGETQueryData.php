<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 * Query parameters for resourceGET
 */
class ResourceGETQueryData
{
    /**
     * Returning orders with by a specific filter.
     * @DTA\Data(field="filter", nullable=true)
     * @DTA\Strategy(name="QueryParameter", options={"type":"string"})
     * @DTA\Validator(name="QueryParameterType", options={"type":"string"})
     * @var string
     */
    public $filter;
}
