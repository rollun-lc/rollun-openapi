<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\MetadataProvider;

use Doctrine\Common\Annotations\AnnotationReader;
use OpenAPI\DataTransfer\Strategy\FieldData;
use Zend\Stdlib\FastPriorityQueue;
use Articus\DataTransfer\Annotation as DTA;
use Articus\DataTransfer\Validator;
use Articus\DataTransfer\Strategy;
use OpenAPI\DataTransfer\Annotation as ODTA;

class Annotation extends \Articus\DataTransfer\MetadataProvider\Annotation
{
    /**
     * Reads metadata for specified class from its annotations
     * @param string $className
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    protected function loadMetadata(string $className): array
    {
        $classStrategies = [];
        $classValidators = [];
        $classFields = [];
        $fieldStrategies = [];
        $fieldValidators = [];

        $classReflection = new \ReflectionClass($className);
        $reader = new AnnotationReader();
        //Read property annotations
        $propertyFilter = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE;
        $requiredFields = [];
        foreach ($classReflection->getProperties($propertyFilter) as $propertyReflection)
        {
            foreach ($this->processPropertyAnnotations($classReflection, $propertyReflection, $reader->getPropertyAnnotations($propertyReflection)) as [$subset, $field, $strategy, $validator, $isRequired])
            {
                $fieldName = $field[0];
                if (!empty($classFields[$subset][$fieldName]))
                {
                    throw new \LogicException(\sprintf('Duplicate field "%s" declaration for subset %s of class %s', $fieldName, $subset, $className));
                }
                $classFields[$subset][$fieldName] = $field;
                $fieldStrategies[$subset][$fieldName] = $strategy;
                $fieldValidators[$subset][$fieldName] = $validator;
                if ($isRequired) {
                    $requiredFields = [$fieldName];
                }
            }
        }
        //Read class annotations
        $propertySubsets = \array_keys($classFields);
        foreach ($this->processClassAnnotations($className, $reader->getClassAnnotations($classReflection), $propertySubsets) as [$subset, $strategy, $validator])
        {
            // Немного костыльный способ добавить в options ключ requiredFields,
            // но требует наименьшее количество изменений базового класса
            if (!array_key_exists('requiredFields', $strategy[1])) {
                $strategy[1]['requiredFields'] = $requiredFields;
            }
            $classStrategies[$subset] = $strategy;
            $classValidators[$subset] = $validator;
        }

        return [$classStrategies, $classValidators, $classFields, $fieldStrategies, $fieldValidators];
    }

    /**
     * @param \ReflectionClass $classReflection
     * @param \ReflectionProperty $propertyReflection
     * @param iterable $annotations
     * @return \Generator tuples (<subset>, <field declaration>, <strategy declaration>, <validator declaration>, <isRequired>)
     * @psalm-return \Generator<array{0: string, 1: array{0: string, 1: null|array{0: string, 2: bool}, 2: null|array{0: string, 2: bool}}, 2: array{0: string, 1: null|array}, 3: array{0: string, 1: array}, 3:bool}>
     * @throws \ReflectionException
     */
    protected function processPropertyAnnotations(\ReflectionClass $classReflection, \ReflectionProperty $propertyReflection, iterable $annotations): \Generator
    {
        /** @psalm-var array<string, array{0: array{0: string, 1: null|array{0: string, 2: bool}, 2: null|array{0: string, 2: bool}}, 1: array{0: string, 1: null|array}, 2: FastPriorityQueue }}> $subsets */
        /** @var array|array[][]|FastPriorityQueue[][] $subsets */
        $subsets = [];
        $emptySubset = function()
        {
            return [null, null, new FastPriorityQueue()];
        };
        //Gather annotation data
        foreach ($annotations as $annotation)
        {
            switch (true)
            {
                case ($annotation instanceof DTA\Data):
                    $subset = $subsets[$annotation->subset] ?? $emptySubset();
                    if ($subset[0] !== null)
                    {
                        throw new \LogicException(\sprintf(
                            'Duplicate data annotation for property %s in metadata subset "%s" of class %s',
                            $propertyReflection->getName(), $annotation->subset, $classReflection->getName()
                        ));
                    }
                    $subset[0] = [
                        $annotation->field ?? $propertyReflection->getName(),
                        $this->calculatePropertyGetter($classReflection, $propertyReflection, $annotation),
                        $this->calculatePropertySetter($classReflection, $propertyReflection, $annotation),
                    ];
                    if (!$annotation->nullable)
                    {
                        $subset[2]->insert([Validator\NotNull::class, null, true], self::MAX_VALIDATOR_PRIORITY);
                    }
                    $subset[3] = $annotation instanceof ODTA\Data ? $annotation->required : true;
                    $subsets[$annotation->subset] = $subset;
                    break;
                case ($annotation instanceof DTA\Strategy):
                    $subset = $subsets[$annotation->subset] ?? $emptySubset();
                    if ($subset[1] !== null)
                    {
                        throw new \LogicException(\sprintf(
                            'Duplicate strategy annotation for property %s in metadata subset "%s" of class %s',
                            $propertyReflection->getName(), $annotation->subset, $classReflection->getName()
                        ));
                    }
                    $subset[1] = [$annotation->name, $annotation->options];
                    $subsets[$annotation->subset] = $subset;
                    break;
                case ($annotation instanceof DTA\Validator):
                    $subset = $subsets[$annotation->subset] ?? $emptySubset();
                    $subset[2]->insert([$annotation->name, $annotation->options, $annotation->blocker], $annotation->priority);
                    $subsets[$annotation->subset] = $subset;
                    break;
            }
        }
        //Fulfil and emit gathered annotation data
        foreach ($subsets as $subset => [$field, $strategy, $validatorQueue, $required])
        {
            if ($field === null)
            {
                throw new \LogicException(\sprintf(
                    'No data annotation for property %s in metadata subset "%s" of class %s',
                    $propertyReflection->getName(), $subset, $classReflection->getName()
                ));
            }
            $strategy = $strategy ?? [Strategy\Whatever::class, null];
            $validator = [Validator\Chain::class, ['links' => $validatorQueue->toArray()]];
            yield [$subset, $field, $strategy, $validator, $required];
        }
    }

    /**
     * @param string $className
     * @param iterable $annotations
     * @param iterable $propertySubsetNames
     * @return \Generator tuples (<subset>, <strategy declaration>, <validator declaration>)
     * @psalm-return \Generator<array{0: string, 1: array{0: string, 1: null|array}, 2: array{0: string, 1: array}}>
     */
    protected function processClassAnnotations(string $className, iterable $annotations, iterable $propertySubsetNames): \Generator
    {
        /** @psalm-var array<string, array{0: array{0: string, 1: null|array}, 1: FastPriorityQueue<array{0: string, 1: array, 2: bool}>}> $subsets */
        /** @var array|array[][]|FastPriorityQueue[][] $subsets */
        $subsets = [];
        $emptySubset = function()
        {
            return [null, new FastPriorityQueue()];
        };
        //Gather annotation data
        foreach ($annotations as $annotation)
        {
            switch (true)
            {
                case ($annotation instanceof DTA\Strategy):
                    $subset = $subsets[$annotation->subset] ?? $emptySubset();
                    if ($subset[0] !== null)
                    {
                        throw new \LogicException(\sprintf('Duplicate strategy annotation for metadata subset "%s" of class %s', $annotation->subset, $className));
                    }
                    $subset[0] = [$annotation->name, $annotation->options];
                    $subsets[$annotation->subset] = $subset;
                    break;
                case ($annotation instanceof DTA\Validator):
                    $subset = $subsets[$annotation->subset] ?? $emptySubset();
                    $subset[1]->insert([$annotation->name, $annotation->options, $annotation->blocker], $annotation->priority);
                    $subsets[$annotation->subset] = $subset;
                    break;
            }
        }
        //Create class subsets required by property annotations
        foreach ($propertySubsetNames as $propertySubsetName)
        {
            $subset = $subsets[$propertySubsetName] ?? $emptySubset();
            if ($subset[0] !== null)
            {
                throw new \LogicException(\sprintf('Excessive strategy annotation for metadata subset "%s" of class %s', $annotation->subset, $className));
            }
            $subset[0] = [FieldData::class, ['type' => $className, 'subset' => $propertySubsetName]];
            $subset[1]->insert([Validator\FieldData::class, ['type' => $className, 'subset' => $propertySubsetName], false], self::MAX_VALIDATOR_PRIORITY);
            $subsets[$propertySubsetName] = $subset;
        }
        //Fulfil and emit gathered annotation data
        foreach ($subsets as $subset => [$strategy, $validatorQueue])
        {
            if ($strategy === null)
            {
                throw new \LogicException(\sprintf('No strategy annotation for metadata subset "%s" of class %s', $subset, $className));
            }
            $validator = [Validator\Chain::class, ['links' => $validatorQueue->toArray()]];
            yield [$subset, $strategy, $validator];
        }
    }
}