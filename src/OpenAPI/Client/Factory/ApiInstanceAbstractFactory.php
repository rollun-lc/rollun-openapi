<?php
declare(strict_types=1);

namespace OpenAPI\Client\Factory;

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
        $lifeCycleToken = null;

        // set life cycle token
        if ($container->has(LifeCycleToken::class)) {
            $lifeCycleToken =  (string)$container->get(LifeCycleToken::class);
        }

        return new $requestedName($lifeCycleToken, $container->get(\Articus\DataTransfer\Service::class));
    }
}

