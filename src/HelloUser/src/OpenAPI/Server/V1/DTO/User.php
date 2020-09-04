<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\Server\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;
use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 */
class User implements ToArrayForDtoInterface
{
    /**
     * @DTA\Data(field="id")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $id;
    /**
     * @DTA\Data(field="name")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $name;

    /**
     * @return array
     */
    public function toArrayForDto(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }
}
