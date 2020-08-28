<?php
declare(strict_types=1);

namespace OpenAPI\Server\MetadataProvider;

use Articus\PathHandler\PluginManager as ArticusPluginManager;
use Interop\Container\ContainerInterface;
use Zend\Cache\StorageFactory;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AnnotationFactory
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class AnnotationFactory implements FactoryInterface
{
    const CACHE_ADAPTER = 'blackhole';

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Annotation($container->get(ArticusPluginManager::class), StorageFactory::factory(['adapter' => self::CACHE_ADAPTER]));
    }
}
