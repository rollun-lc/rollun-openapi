<?php


namespace OpenAPI\Config;


use Articus\PathHandler\Attribute\Transfer as ArticusTransfer;
use Articus\PathHandler\PluginManager;
use Articus\PathHandler\RouteInjection\Factory as RouteInjectionFactory;
use Articus\PathHandler\Router\FastRoute;
use OpenAPI\Server\Attribute\Factory\Transfer as OpenApiTransfer;
use OpenAPI\Server\MetadataProvider\Annotation;
use OpenAPI\Server\MetadataProvider\AnnotationFactory as AnnotationFactory;
use OpenAPI\Server\Middleware\InternalServerError;
use OpenAPI\Server\Producer\Factory\Transfer as ProducerTransferFactory;
use OpenAPI\Server\Producer\Transfer as ProducerTransfer;
use Psr\Http\Message\ResponseInterface;
use Mezzio\Router\RouterInterface;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

class PathHandlerConfig
{
    public static function getConfig(): array
    {
        return [
            'dependencies' => [
                'aliases' => [
                    RouterInterface::class => FastRoute::class,
                ],
                'invokables' => [
                    PluginManager::class => PluginManager::class,
                ],
                'factories' => [
                    FastRoute::class => RouteInjectionFactory::class,
                    InternalServerError::class => ConfigAbstractFactory::class,
                    Annotation::class => AnnotationFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                InternalServerError::class => [
                    ResponseInterface::class
                ]
            ],
            RouteInjectionFactory::class => [
                'producers' => [
                    'factories' => [
                        ProducerTransfer::class => ProducerTransferFactory::class
                    ]
                ],
                'attributes' => [
                    'factories' => [
                        ArticusTransfer::class => OpenApiTransfer::class
                    ]
                ],
                'metadata' => Annotation::class,
                'handlers' => PluginManager::class
            ],
        ];
    }
}