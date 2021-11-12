<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Validator;

use Articus\DataTransfer\Utility;
use Articus\DataTransfer\Validator\ValidatorInterface;

class FieldData extends \Articus\DataTransfer\Validator\FieldData
{
    /**
     * @inheritDoc
     */
    public function validate($data): array
    {
        $result = [];
        $map = new Utility\MapAccessor($data);
        if ($map->accessible())
        {
            foreach ($this->fields as [$fieldName, $validator])
            {
                if (array_key_exists($fieldName, $data)) {
                    /** @var ValidatorInterface $validator */
                    $fieldViolations = $validator->validate($map->get($fieldName));
                    if (!empty($fieldViolations))
                    {
                        $result[self::INVALID_INNER][$fieldName] = $fieldViolations;
                    }
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