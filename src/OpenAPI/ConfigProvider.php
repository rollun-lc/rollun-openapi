<?php
declare(strict_types=1);

namespace OpenAPI;

use Articus\DataTransfer\ClassMetadataProviderInterface;
use Articus\DataTransfer\FieldMetadataProviderInterface;
use Articus\DataTransfer\MetadataProvider\Annotation as DataTransferAnnotation;
use Articus\DataTransfer\MetadataProvider\Factory\Annotation as DataTransferAnnotationFactory;
use Articus\DataTransfer\Service as DataTransferService;
use Articus\DataTransfer\Factory as DataTransferServiceFactory;
use Articus\DataTransfer\Strategy\PluginManager as StrategyPluginManager;
use Articus\DataTransfer\Strategy\Factory\PluginManager as StrategyPluginManagerFactory;
use Articus\DataTransfer\Validator\Collection;
use Articus\DataTransfer\Validator\Factory as ValidatorFactory;
use Articus\DataTransfer\Validator\PluginManager as ValidatorPluginManager;
use Articus\DataTransfer\Validator\Factory\PluginManager as ValidatorPluginManagerFactory;
use Articus\DataTransfer\Validator\TypeCompliant;
use OpenAPI\Client\Factory\ApiInstanceAbstractFactory;
use OpenAPI\Client\Factory\ConfigurationAbstractFactory;
use OpenAPI\Server\MetadataProvider\Annotation;
use OpenAPI\Server\MetadataProvider\Annotation as PathHandlerAnnotation;
use Articus\PathHandler\PluginManager as ArticusPluginManager;
use Articus\PathHandler\RouteInjection\Factory as RouteInjectionFactory;
use Articus\PathHandler\Router\FastRoute;
use OpenAPI\Server\MetadataProvider\AnnotationFactory as PathHandlerAnnotationFactory;
use OpenAPI\Server\Middleware\InternalServerError;
use OpenAPI\Server\Producer\Factory\Transfer as ProducerTransferFactory;
use OpenAPI\Server\Producer\Transfer as ProducerTransfer;
use OpenAPI\Server\Strategy;
use OpenAPI\Server\Validator;
use OpenAPI\Server\Writer\Messages;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

/**
 * Class ConfigProvider
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'log' => [
                LoggerInterface::class => [
                    'writers' => [
                        [
                            'name' => Messages::class,
                            'options' => [
                                'filters' => [
                                    [
                                        'name' => 'priority',
                                        'options' => [
                                            'operator' => '<',
                                            'priority' => 4,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ],
            'dependencies' => [
                'aliases' => [
                    RouterInterface::class => FastRoute::class,

                    // DataTransfer config
                    ClassMetadataProviderInterface::class => DataTransferAnnotation::class,
                    FieldMetadataProviderInterface::class => DataTransferAnnotation::class
                ],
                'invokables' => [
                    ArticusPluginManager::class => ArticusPluginManager::class,
                ],
                'factories' => [
                    FastRoute::class => RouteInjectionFactory::class,
                    InternalServerError::class => ConfigAbstractFactory::class,
                    PathHandlerAnnotation::class => PathHandlerAnnotationFactory::class,

                    // DataTransfer config
                    DataTransferService::class => DataTransferServiceFactory::class,
                    DataTransferAnnotation::class => DataTransferAnnotationFactory::class,
                    StrategyPluginManager::class => StrategyPluginManagerFactory::class,
                    ValidatorPluginManager::class => ValidatorPluginManagerFactory::class,
                    \Zend\Validator\ValidatorPluginManager::class => \Zend\Validator\ValidatorPluginManagerFactory::class
                ],
                'abstract_factories' => [
                    ApiInstanceAbstractFactory::class,
                    ConfigurationAbstractFactory::class,
                ]
            ],
            ConfigAbstractFactory::class => [
                InternalServerError::class => [
                    ResponseInterface::class
                ]
            ],
            DataTransferAnnotation::class => [
                'cache' => [
                    'adapter' => 'blackhole'
                ],
            ],
            StrategyPluginManager::class => [
                'invokables' => [
                    Strategy\Date::class => Strategy\Date::class,
                    Strategy\DateTime::class => Strategy\DateTime::class,
                    Strategy\QueryParameter::class => Strategy\QueryParameter::class,
                    Strategy\QueryParameterArray::class => Strategy\QueryParameterArray::class,
                ],
                'aliases' => [
                    'Date' => Strategy\Date::class,
                    'DateTime' => Strategy\DateTime::class,
                    'QueryParameter' => Strategy\QueryParameter::class,
                    'QueryParameterArray' => Strategy\QueryParameterArray::class,
                ]
            ],
            ValidatorPluginManager::class => [
                'factories' => [
                    TypeCompliant::class => ValidatorFactory\TypeCompliant::class,
                    Collection::class => ValidatorFactory\Collection::class,
                ],
                'aliases' => [
                    'Dictionary' => TypeCompliant::class,
                    'Collection' => Collection::class,
                ],
                'abstract_factories' => [
                    // Поддержка валидаторов из Zend validator plugin manager
                    ValidatorFactory\Zend::class
                ]
            ],
            // Zend validators plugin manager config
            'validators' => [
                'invokables' => [
                    Validator\Type::class => Validator\Type::class,
                    Validator\Enum::class => Validator\Enum::class,
                    Validator\QueryParameterType::class => Validator\QueryParameterType::class,
                    Validator\QueryParameterArrayType::class => Validator\QueryParameterArrayType::class,
                ],
                'aliases' => [
                    'Type' => Validator\Type::class,
                    'Enum' => Validator\Enum::class,
                    'QueryParameterType' => Validator\QueryParameterType::class,
                    'QueryParameterArrayType' => Validator\QueryParameterArrayType::class,
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
                        \Articus\PathHandler\Attribute\Transfer::class => \OpenAPI\Server\Attribute\Factory\Transfer::class
                    ]
                ],
                'metadata' => PathHandlerAnnotation::class,
                'handlers' => ArticusPluginManager::class
            ],
        ];
    }
}
