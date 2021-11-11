<?php

declare(strict_types=1);

namespace OpenAPI\DataTransfer\MetadataProvider\Factory;

use Articus\DataTransfer\MetadataProvider\Factory\Annotation as BaseAnnotationFactory;
use Interop\Container\ContainerInterface;
use OpenAPI\DataTransfer\MetadataProvider\Annotation;

class AnnotationFactory extends BaseAnnotationFactory
{
    public function __construct(string $configKey = Annotation::class)
    {
        parent::__construct($configKey);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = \array_merge(self::DEFAULT_CONFIG, $this->getServiceConfig($container), $options ?? []);
        $cacheStorage = $this->getCacheStorage($container, $config['cache']);
        return new Annotation($cacheStorage);
    }
}