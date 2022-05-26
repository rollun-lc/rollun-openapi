<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\Validator\Factory;

use Articus\DataTransfer\FieldMetadataProviderInterface;
use Articus\DataTransfer\Validator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use OpenAPI\DataTransfer\Validator\RequiredFields;
use Psr\Container\ContainerInterface;

class RequiredFieldsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $type = $options['type'] ?? null;
        if ($type === null) {
            throw new \LogicException('Option "type" is required');
        }
        $subset = $options['subset'] ?? '';
        $metadataProvider = $this->getMetadataProvider($container);
        $fields = [];
        foreach ($metadataProvider->getClassFields($type, $subset) as [$fieldName, $getter, $setter]) {
            $isRequired = $options['required'][$fieldName] ?? false;
            $fields[] = [$fieldName, $isRequired];
        }
        return new RequiredFields($fields);
    }

    protected function getMetadataProvider(ContainerInterface $container): FieldMetadataProviderInterface
    {
        return $container->get(FieldMetadataProviderInterface::class);
    }

    protected function getValidatorManager(ContainerInterface $container): Validator\PluginManager
    {
        return $container->get(Validator\PluginManager::class);
    }
}
