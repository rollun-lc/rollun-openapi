<?php
declare(strict_types=1);

namespace OpenAPI\Client\Factory;

use GuzzleHttp\Client;
use Interop\Container\ContainerInterface;
use rollun\logger\LifeCycleToken;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class ApiInstanceAbstractFactory
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class ApiInstanceAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return defined($requestedName . '::IS_API_CLIENT');
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $headers = [];

        // set life cycle token
        if ($container->has(LifeCycleToken::class)) {
            $headers = ['LifeCycleToken' => (string)$container->get(LifeCycleToken::class)];
        }

        return new $requestedName(new Client(['headers' => $headers]));
    }
}

