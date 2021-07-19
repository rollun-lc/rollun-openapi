<?php
declare(strict_types=1);

namespace OpenAPI\Client\Factory;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class ApiInstanceAbstractFactory
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class ApiInstanceAbstractFactory implements AbstractFactoryInterface
{
    public const KEY = self::class;

    public const KEY_CONFIGURATION = 'configuration';

    protected const LIFECYCLE_TOKEN = 'rollun\logger\LifeCycleToken';

    public const KEY_CLASS = 'class';

    /**
     * @inheritDoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;
        $className = $config[self::KEY_CLASS] ?? $requestedName;
        return defined($className . '::IS_API_CLIENT');
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;
        $className = $config[self::KEY_CLASS] ?? $requestedName;

        $configuration = null;

        // define configuration
        if (isset($config[self::KEY_CONFIGURATION])) {
            $configuration = $container->get($config[self::KEY_CONFIGURATION]);
        } elseif (defined($className . '::CONFIGURATION_CLASS')) {
            $configurationClass = $className::CONFIGURATION_CLASS;
            $configuration = $container->get($configurationClass);
        }

        // set life cycle token
        if ($container->has(static::LIFECYCLE_TOKEN)) {
            $lifeCycleToken = (string)$container->get(static::LIFECYCLE_TOKEN);
        } else {
            throw new ServiceNotCreatedException(static::LIFECYCLE_TOKEN . ' not found in container.');
        }

        return new $className(
            $container->get(\Articus\DataTransfer\Service::class),
            $container->get(LoggerInterface::class),
            $lifeCycleToken,
            $configuration
        );
    }
}

