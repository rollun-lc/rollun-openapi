<?php


namespace OpenAPI\Client\Factory;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Interop\Container\ContainerInterface;
use OpenAPI\Client\Api\ApiInterface;
use Psr\Log\LoggerInterface;
use rollun\logger\LifeCycleToken;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class ApiAbstractFactory implements AbstractFactoryInterface
{
    public const KEY = self::class;

    public const KEY_CLASS = 'class';

    public const KEY_CLIENT = 'client';

    public const KEY_CONFIGURATION = 'configuration';

    public const KEY_HEADER_SELECTOR = 'headerSelector';

    public const KEY_HOST_INDEX = 'hostIndex';

    public const KEY_HOST_URL = 'hostUrl';

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;
        $className = $config[self::KEY_CLASS] ?? $requestedName;
        return is_a($className, ApiInterface::class, true);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? null;

        $className = $config[self::KEY_CLASS] ?? $requestedName;

        // set life cycle token
        $lifeCycleToken = (string) $container->get(LifeCycleToken::class);

        // TODO
         $client = $options[self::KEY_CLIENT] ?? $config[self::KEY_CLIENT] ?? null;
        if (is_string($client)) {
            $client = $container->get($client);
            if (!$client instanceof ClientInterface) {
                throw new \Exception('Client must implement ' . ClientInterface::class);
            }
        }
        $clientConfig = $client === null ? [] : $client->getConfig();
        $clientConfig = array_merge_recursive([
            'headers' => [
                'LifeCycleToken' => $lifeCycleToken
            ],
        ], $clientConfig);
        $client = new Client($clientConfig);

        $configuration = $options[self::KEY_CONFIGURATION] ?? $config[self::KEY_CONFIGURATION] ?? $className::CONFIGURATION_CLASS;
        if (is_string($configuration)) {
            $configuration = $container->get($configuration);
        }

        $headerSelector = $options[self::KEY_HEADER_SELECTOR] ?? $config[self::KEY_HEADER_SELECTOR] ?? null;
        $hostIndex = $options[self::KEY_HOST_INDEX] ?? $config[self::KEY_HOST_INDEX] ?? 0;

        $logger = $container->get(LoggerInterface::class);

        $instance = new $className(
            $client,
            $configuration,
            $headerSelector,
            $hostIndex,
            $logger
        );

        return $instance;
    }
}