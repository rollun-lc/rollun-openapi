<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class User
{
    /**
     * @DTA\Data(field="id")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public string $id;
    /**
     * @DTA\Data(field="name")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public string $name;
}
