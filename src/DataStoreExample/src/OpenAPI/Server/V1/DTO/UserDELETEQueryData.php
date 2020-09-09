<?php
declare(strict_types=1);

namespace DataStoreExample\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 * Query parameters for userDELETE
 */
class UserDELETEQueryData
{
    /**
     * @DTA\Data(field="rql", nullable=true)
     * @DTA\Strategy(name="QueryParameter", options={"type":"string"})
     * @DTA\Validator(name="QueryParameterType", options={"type":"string"})
     * @var string
     */
    public $rql;
}
