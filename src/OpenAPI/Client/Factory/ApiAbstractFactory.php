<?php


namespace OpenAPI\Client\Factory;


use GuzzleHttp\Client;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use OpenAPI\Client\Api\ApiInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ApiAbstractFactory implements AbstractFactoryInterface
{
    protected const KEY = self::class;

    protected const LIFECYCLE_TOKEN = 'rollun\logger\LifeCycleToken';

    protected const KEY_CLIENT = 'client';

    protected const KEY_CONFIGURATION = 'configuration';

    protected const KEY_HEADER_SELECTOR = 'headerSelector';

    protected const KEY_HOST_INDEX = 'hostIndex';

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return is_a($requestedName, ApiInterface::class, true);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')[$requestedName] ?? null;

        // set life cycle token
        if ($container->has(static::LIFECYCLE_TOKEN)) {
            $lifeCycleToken = (string)$container->get(static::LIFECYCLE_TOKEN);
        } else {
            throw new ServiceNotCreatedException(static::LIFECYCLE_TOKEN . ' not found in container.');
        }

        $client = $options[self::KEY_CLIENT] ?? $config[self::KEY_CLIENT] ?? null;
        if (is_string($client)) {
            $client = $container->get($client);
        }
        $clientConfig = $client && $client instanceof Client ? $client->getConfig() : [];
        $clientConfig = array_merge([
            'headers' => [
                'LifeCycleToken' => $lifeCycleToken
            ],
            'timeout' => 120
        ], $clientConfig);
        $client = new Client($clientConfig);

        $configuration = $options[self::KEY_CONFIGURATION] ?? null;
        if (is_string($configuration)) {
            $configuration = $container->get($configuration);
        }

        $headerSelector = $config[self::KEY_HEADER_SELECTOR] ?? null;
        $hostIndex = $config[self::KEY_HOST_INDEX] ?? 0;

        $logger = $container->get(LoggerInterface::class);

        $instance = new $requestedName(
            $client,
            $configuration,
            $headerSelector,
            $hostIndex,
            $logger
        );

        return $instance;
    }
}