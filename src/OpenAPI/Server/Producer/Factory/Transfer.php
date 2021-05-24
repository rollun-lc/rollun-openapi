<?php
declare(strict_types=1);

namespace OpenAPI\Server\Producer\Factory;

use Articus\DataTransfer\Service as DTService;
use Articus\PathHandler\Producer\Factory\Transfer as Base;
use Interop\Container\ContainerInterface;
use OpenAPI\Server\Producer\Transfer as TransferInstance;
use OpenAPI\Server\Response\MessageReaderInterface;
use Psr\Http\Message\StreamInterface;

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
        // prepare response type
        $subset = $options['subset'] ?? '';
        $responseType = !empty($options['responseType']) ? $options['responseType'] : null;

        return new TransferInstance(
            $container->get(StreamInterface::class),
            $container->get(DTService::class),
            $container->get(MessageReaderInterface::class),
            $subset,
            $responseType
        );
    }
}