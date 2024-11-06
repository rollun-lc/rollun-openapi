<?php
declare(strict_types=1);

namespace OpenAPI\Server\Attribute\Factory;

use Articus\DataTransfer\Service as DTService;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Articus\PathHandler as PH;

/**
 * Class Transfer
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Transfer implements FactoryInterface
{
    protected static $defaultInstanciator;

    public function __construct()
    {
        self::$defaultInstanciator = static function (string $type)
        {
            return new $type();
        };
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $optionsObject = new PH\Attribute\Options\Transfer($options);
        $instanciator = ($optionsObject->instanciator === null)
            ? self::$defaultInstanciator
            : $container->get($optionsObject->instanciator);
        return new \OpenAPI\Server\Attribute\Transfer(
            $container->get(DTService::class),
            $optionsObject->source,
            $optionsObject->type,
            $optionsObject->subset,
            $optionsObject->objectAttr,
            $instanciator,
            $optionsObject->instanciatorArgAttrs,
            $optionsObject->errorAttr
        );
    }
}