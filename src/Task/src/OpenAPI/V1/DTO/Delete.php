<?php
declare(strict_types=1);

namespace Task\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Delete
{
    /**
     * @DTA\Data(field="isDeleted")
     * @DTA\Validator(name="Type", options={"type":"bool"})
     * @var bool
     */
    public $is_deleted;
}
