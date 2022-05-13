<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class ErrorResult
{
    /**
     * @DTA\Data(field="messages", nullable=true)
     * @DTA\Strategy(name="ObjectArray", options={"type":\HelloUser\OpenAPI\V1\DTO\Message::class})
     * @DTA\Validator(name="Collection", options={"validators":{
     *     {"name":"TypeCompliant", "options":{"type":\HelloUser\OpenAPI\V1\DTO\Message::class}}
     * }})
     * @var \HelloUser\OpenAPI\V1\DTO\Message[]
     */
    public array $messages;
}
