<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Strategy;

use Articus\DataTransfer\Exception\InvalidData;
use Articus\DataTransfer\Strategy\StrategyInterface;
use Articus\DataTransfer\Utility\MapAccessor;
use Articus\DataTransfer\Validator;
use OpenAPI\DataTransfer\Utility\PropertyAccessor;

/**
 * Переделанная оригинальная FieldData стратегия для поддержки не инициализированных свойств.
 *
 * @see \Articus\DataTransfer\Strategy\FieldData
 */
class FieldData implements StrategyInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @psalm-var iterable<array{0: string, 1: null|array{0: string, 1: bool}, 2: null|array{0: string, 1: bool}, 3: StrategyInterface}>
     * @var iterable
     */
    protected $typeFields;

    /**
     * @var bool
     */
    protected $extractStdClass = false;

    /**
     * @param string $type
     * @param iterable $typeFields list of tuples (<field name>, (<name of property or method to get field value>, <flag if getter is method>), (<name of property or method to set field value>, <flag if setter is method>), <strategy>, <hasMethod>)
     * @param bool $extractStdClass
     */
    public function __construct(string $type, iterable $typeFields, bool $extractStdClass)
    {
        $this->type = $type;
        $this->typeFields = $typeFields;
        $this->extractStdClass = $extractStdClass;
    }

    /**
     * @inheritDoc
     */
    public function extract($from)
    {
        if (!($from instanceof $this->type))
        {
            throw new InvalidData(
                InvalidData::DEFAULT_VIOLATION,
                new \InvalidArgumentException(\sprintf(
                    'Extraction can be done only from %s, not %s',
                    $this->type, \is_object($from) ? \get_class($from) : \gettype($from)
                ))
            );
        }

        $result = ($this->extractStdClass) ? new \stdClass() : [];
        $map = new MapAccessor($result);
        $object = new PropertyAccessor($from);
        foreach ($this->typeFields as [$fieldName, $getter, $setter, $strategy, $hasser])
        {
            /** @var StrategyInterface $strategy */
            try
            {
                if ($object->has($hasser, true)) {
                    $rawValue = $object->get($getter);
                    $fieldValue = $strategy->extract($rawValue);
                    $map->set($fieldName, $fieldValue);
                }
            }
            catch (InvalidData $e)
            {
                $violations = [Validator\FieldData::INVALID_INNER => [$fieldName => $e->getViolations()]];
                throw new InvalidData($violations, $e);
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function hydrate($from, &$to): void
    {
        $map = new MapAccessor($from);
        if (!$map->accessible())
        {
            throw new InvalidData(
                InvalidData::DEFAULT_VIOLATION,
                new \InvalidArgumentException(\sprintf(
                    'Hydration can be done only from key-value map, not %s',
                    \is_object($from) ? \get_class($from) : \gettype($from)
                ))
            );
        }
        if (!($to instanceof $this->type))
        {
            throw new InvalidData(
                InvalidData::DEFAULT_VIOLATION,
                new \InvalidArgumentException(\sprintf(
                    'Hydration can be done only to %s, not %s',
                    $this->type, \is_object($to) ? \get_class($to) : \gettype($to)
                ))
            );
        }
        $object = new PropertyAccessor($to);
        foreach ($this->typeFields as [$fieldName, $getter, $setter, $strategy, $hasser])
        {
            /** @var StrategyInterface $strategy */
            if (($setter !== null) && $map->has($fieldName))
            {
                try
                {
                    $rawValue = $object->has($hasser, true) ? $object->get($getter) : null;
                    $fieldValue = $map->get($fieldName);
                    $strategy->hydrate($fieldValue, $rawValue);
                    $object->set($setter, $rawValue);
                }
                catch (InvalidData $e)
                {
                    $violations = [Validator\FieldData::INVALID_INNER => [$fieldName => $e->getViolations()]];
                    throw new InvalidData($violations, $e);
                }
            }
        }
    }

    public function merge($from, &$to): void
    {
        $fromMap = new MapAccessor($from);
        if (!$fromMap->accessible())
        {
            throw new InvalidData(
                InvalidData::DEFAULT_VIOLATION,
                new \InvalidArgumentException(\sprintf(
                    'Merge can be done only for key-value map, not %s',
                    \is_object($from) ? \get_class($from) : \gettype($from)
                ))
            );
        }
        $toMap = new MapAccessor($to);
        if (!$toMap->accessible())
        {
            throw new InvalidData(
                InvalidData::DEFAULT_VIOLATION,
                new \InvalidArgumentException(\sprintf(
                    'Merge can be done only into key-value map, not %s',
                    \is_object($to) ? \get_class($to) : \gettype($to)
                ))
            );
        }

        foreach ($this->typeFields as [$fieldName, $getter, $setter, $strategy])
        {
            /** @var StrategyInterface $strategy */
            if (($setter !== null) && $fromMap->has($fieldName))
            {
                try
                {
                    $toFieldValue = $toMap->get($fieldName);
                    $fromFieldValue = $fromMap->get($fieldName);
                    $strategy->merge($fromFieldValue, $toFieldValue);
                    $toMap->set($fieldName, $toFieldValue);
                }
                catch (InvalidData $e)
                {
                    $violations = [Validator\FieldData::INVALID_INNER => [$fieldName => $e->getViolations()]];
                    throw new InvalidData($violations, $e);
                }
            }
        }
    }
}
