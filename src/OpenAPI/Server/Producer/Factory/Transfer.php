<?php
declare(strict_types=1);

namespace OpenAPI\Server\Producer\Factory;

use Articus\DataTransfer\Service as DTService;
use Articus\PathHandler\Producer\Factory\Transfer as Base;
use Interop\Container\ContainerInterface;
use OpenAPI\Server\Producer\Transfer as TransferInstance;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Transfer
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Transfer extends Base
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mapper = null;
        if (!empty($options['mapper'])) {
            $mapperConfig = $options['mapper'];
            switch (true) {
                case (\is_string($mapperConfig) && $container->has($mapperConfig)):
                    $mapper = $container->get($mapperConfig);
                    if (!self::isMapper($mapper)) {
                        throw new \LogicException(\sprintf('Invalid mapper %s.', $mapperConfig));
                    }
                    break;
                case (\is_array($mapperConfig)
                    && isset($mapperConfig['name'], $mapperConfig['options'])
                    && ($container instanceof ServiceLocatorInterface)
                    && $container->has($mapperConfig['name'])
                ):
                    $mapper = $container->build($mapperConfig['name'], $mapperConfig['options']);
                    if (!self::isMapper($mapper)) {
                        throw new \LogicException(\sprintf('Invalid mapper %s.', $mapperConfig['name']));
                    }
                    break;
                case (self::isMapper($mapperConfig)):
                    //Allow direct pass of object or callback
                    $mapper = $mapperConfig;
                    break;
                default:
                    throw new \LogicException('Invalid mapper.');
            }
        }

        // prepare response type
        $responseType = !empty($options['responseType']) ? $options['responseType'] : null;

        return new TransferInstance($container->get(StreamInterface::class), $container->get(DTService::class), $container->get(LoggerInterface::class), $mapper, $responseType);
    }
}