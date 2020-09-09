<?php
declare(strict_types=1);

namespace OpenAPI\Server\Attribute\Factory;

use Articus\DataTransfer;
use Articus\PathHandler;
use Interop\Container\ContainerInterface;
use OpenAPI\Server;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class Transfer
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Transfer implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Server\Attribute\Transfer($container->get(DataTransfer\Service::class), new PathHandler\Attribute\Options\Transfer($options));
    }
}