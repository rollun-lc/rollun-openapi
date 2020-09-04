<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 */
class Hello implements ToArrayForDtoInterface
{
    /**
     * @DTA\Data(field="message")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $message;

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        return ['message' => $this->message];
    }
}
