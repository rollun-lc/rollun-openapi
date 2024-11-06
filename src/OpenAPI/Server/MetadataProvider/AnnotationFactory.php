<?php
declare(strict_types=1);

namespace OpenAPI\Server\MetadataProvider;

use Articus\PathHandler\PluginManager as ArticusPluginManager;
use Psr\Container\ContainerInterface;
use Laminas\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Laminas\Cache\Storage\Adapter\BlackHole;
use Laminas\ServiceManager\Factory\FactoryInterface;

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
        $factory = $container->get(StorageAdapterFactoryInterface::class);
        //$test = $factory->create('filesystem');
        //$cache = $factory->createFromArrayConfiguration(['adapter' => self::CACHE_ADAPTER]);
        $cache = new SimpleCacheDecorator(new BlackHole());
        //$cache = new CacheItemPoolDecorator($cache);
        return new Annotation(
            $container->get(ArticusPluginManager::class),
            $cache
        );
    }
}
