<?php
declare(strict_types=1);

namespace OpenAPI\Client\Factory;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class ApiInstanceAbstractFactory
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class RestAbstractFactory implements AbstractFactoryInterface
{
    public const KEY = self::class;

    //public const KEY_CONFIGURATION = 'configuration';

    public const KEY_API_NAME = 'apiName';

    //public const KEY_HOST_INDEX = 'hostIndex';

    //public const KEY_HOST_URL = 'hostUrl';

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

        //$configuration = null;

        // define configuration
        /*if (isset($config[self::KEY_CONFIGURATION])) {
            $configurationClass = $config[self::KEY_CONFIGURATION];
            $configuration = $container->get($configurationClass);
        } elseif (defined($className . '::CONFIGURATION_CLASS')) {
            $configurationClass = $className::CONFIGURATION_CLASS;
            $configuration = $container->get($configurationClass);
        }*/

        $apiName = $config[self::KEY_API_NAME] ?? $className::API_NAME;
        $api = $container->get($apiName);
        /*$api = $container->build($apiName, [
            'configuration' => $configuration,
            'hostIndex' => $config[self::KEY_HOST_INDEX] ?? 0,
        ]);*/

        $transfer = $container->get(\Articus\DataTransfer\Service::class);
        $logger = $container->get(LoggerInterface::class);


        return new $className($api, $transfer, $logger);
    }
}

