<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Validator\Factory;

use Articus\DataTransfer\Validator\Factory\FieldData as BaseFieldDataFactory;
use Interop\Container\ContainerInterface;
use OpenAPI\DataTransfer\Validator\FieldData;

class FieldDataFactory extends BaseFieldDataFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $type = $options['type'] ?? null;
        if ($type === null)
        {
            throw new \LogicException('Option "type" is required');
        }
        $subset = $options['subset'] ?? '';
        $metadataProvider = $this->getMetadataProvider($container);
        $validatorManager = $this->getValidatorManager($container);
        $fields = [];
        foreach ($metadataProvider->getClassFields($type, $subset) as [$fieldName, $getter, $setter])
        {
            $validator = $validatorManager->get(...$metadataProvider->getFieldValidator($type, $subset, $fieldName));
            $fields[] = [$fieldName, $validator];
        }
        return new FieldData($fields);
    }
}
