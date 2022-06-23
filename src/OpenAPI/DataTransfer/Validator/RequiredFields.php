<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Validator;

use Articus\DataTransfer\Utility;
use Articus\DataTransfer\Validator\ValidatorInterface;

class RequiredFields implements ValidatorInterface
{
    public const INVALID = 'objectInvalid';

    public function __construct(private array $fields)
    {
    }

    public function validate($data): array
    {
        $result = [];
        $map = new Utility\MapAccessor($data);
        if ($map->accessible())
        {
            foreach ($this->fields as [$fieldName, $isRequired])
            {
                if ($isRequired && !array_key_exists($fieldName, $data)) {
                    $result[self::INVALID] = "Property $fieldName is required.";
                }
            }
        }
        else
        {
            $result[self::INVALID] = \sprintf(
                'Invalid data: expecting key-value map, not %s.', \is_object($data) ? \get_class($data) : \gettype($data)
            );
        }
        return $result;
    }
}
