<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Strategy\Factory;

use Articus\DataTransfer\Strategy\Factory\FieldData as BaseFieldDataGactory;
use Interop\Container\ContainerInterface;
use OpenAPI\DataTransfer\Strategy\FieldData;

class FieldDataFactory extends BaseFieldDataGactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $type = $options['type'] ?? null;
        if ($type === null)
        {
            throw new \LogicException('Option "type" is required');
        }
        elseif (!\class_exists($type))
        {
            throw new \LogicException(\sprintf('Type "%s" does not exist', $type));
        }
        $subset = $options['subset'] ?? '';
        $extractStdClass = $options['extract_std_class'] ?? false;
        $metadataProvider = $this->getMetadataProvider($container);
        $strategyManager = $this->getStrategyManager($container);
        $typeFields = [];
        foreach ($metadataProvider->getClassFields($type, $subset) as [$fieldName, $getter, $setter])
        {
            $strategy = $strategyManager->get(...$metadataProvider->getFieldStrategy($type, $subset, $fieldName));
            $typeFields[] = [$fieldName, $getter, $setter, $strategy];
        }
        return new FieldData($type, $typeFields, $extractStdClass, $options['requiredFields']);
    }
}