<?php


namespace OpenAPI\Client\Factory;


use Psr\Container\ContainerInterface;
use OpenAPI\Client\Configuration\ConfigurationAbstract;
use OpenAPI\Client\Configuration\ConfigurationInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class ConfigurationAbstractFactory
 * @package OpenAPI\Client\Factory
 */
class ConfigurationAbstractFactory implements AbstractFactoryInterface
{
    public const KEY = self::class;

    public const KEY_CLASS = 'class';

    public const KEY_CONFIG = 'config';

    public const KEY_AUTHENTICATOR = 'authenticator';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $serviceClass = $this->getClassName($container, $requestedName);

        return is_a($serviceClass, ConfigurationAbstract::class, true);
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return ConfigurationInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceClass = $this->getClassName($container, $requestedName);

        $instance = new $serviceClass();

        $config = $container->get('config')[self::KEY][$requestedName] ?? [];

        if (isset($config[self::KEY_AUTHENTICATOR]) || isset($config[self::KEY_CONFIG][self::KEY_AUTHENTICATOR])) {
            $authenticator = $config[self::KEY_AUTHENTICATOR] ?? $config[self::KEY_CONFIG][self::KEY_AUTHENTICATOR];
            if (is_string($authenticator)) {
                $authenticator = $container->get($authenticator);
            }

            $instance->setAuthenticator($authenticator);
            unset($config[self::KEY_CONFIG][self::KEY_AUTHENTICATOR]);
        }

        if (isset($config[self::KEY_CONFIG])) {
            // TODO
            foreach ($config[self::KEY_CONFIG] as $key => $value) {
                $setter = 'set' . $key;
                if (method_exists($instance, $setter)) {
                    $instance->{$setter}($value);
                }
            }
        }

        return $instance;
    }

    /**
     * @param $container
     * @param $requestedName
     *
     * @return string
     */
    protected function getClassName(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;

        if (isset($config[self::KEY_CLASS])) {
            $className = $config[self::KEY_CLASS];
        } elseif (is_string($config)) {
            $className = $config;
        } else {
            $className = $requestedName;
        }

        return $className;
    }
}