<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Strategy;

use Articus\DataTransfer\Exception\InvalidData;
use Articus\DataTransfer\Strategy\StrategyInterface;
use Articus\DataTransfer\Utility;
use Articus\DataTransfer\Validator;

class FieldData extends \Articus\DataTransfer\Strategy\FieldData
{
    /**
     * @var array
     */
    private $requiredFields;

    public function __construct(string $type, iterable $typeFields, bool $extractStdClass, array $requiredFields)
    {
        parent::__construct($type, $typeFields, $extractStdClass);
        $this->requiredFields = $requiredFields;
    }

    /**
     * @inheritDoc
     */
    public function extract($from)
    {
        if (!($from instanceof $this->type))
        {
            throw new \LogicException(\sprintf(
                'Extraction can be done only from %s, not %s',
                $this->type, \is_object($from) ? \get_class($from) : \gettype($from)
            ));
        }

        $result = ($this->extractStdClass) ? new \stdClass() : [];
        $map = new Utility\MapAccessor($result);
        $object = new Utility\PropertyAccessor($from);
        foreach ($this->typeFields as [$fieldName, $getter, $setter, $strategy])
        {
            /** @var StrategyInterface $strategy */
            try
            {
//                if (array_key_exists($fieldName, (array)$from)) {
                    $rawValue = $object->get($getter);
                    $fieldValue = $strategy->extract($rawValue);
                    $map->set($fieldName, $fieldValue);
//                } elseif ($this->isRequired($fieldName)) {
//                    throw new InvalidData(['Field is required.']);
//                    $map->set($fieldName, null);
//                }
            }
            catch (InvalidData $e)
            {
                $violations = [Validator\FieldData::INVALID_INNER => [$fieldName => $e->getViolations()]];
                throw new InvalidData($violations, $e);
            }
        }
        return $result;
    }

    private function isRequired(string $fieldName): bool
    {
        return in_array($fieldName, $this->requiredFields);
    }
}