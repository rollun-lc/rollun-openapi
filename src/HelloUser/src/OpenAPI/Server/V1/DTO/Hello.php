<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Hello
{
    /**
     * @DTA\Data(field="message")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $message;
}
