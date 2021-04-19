<?php
declare(strict_types=1);

namespace OpenAPI;

use Articus\DataTransfer\Service as DataTransferService;
use Articus\DataTransfer\ServiceFactory as DataTransferServiceFactory;
use Articus\DataTransfer\Validator\Collection;
use Articus\DataTransfer\Validator\Dictionary;
use Articus\DataTransfer\Validator\Factory as ValidatorFactory;
use OpenAPI\Client\Factory\ApiInstanceAbstractFactory;
use OpenAPI\Client\Factory\ConfigurationAbstractFactory;
use OpenAPI\Server\MetadataProvider\Annotation;
use Articus\PathHandler\PluginManager as ArticusPluginManager;
use Articus\PathHandler\RouteInjection\Factory as RouteInjectionFactory;
use Articus\PathHandler\Router\FastRoute;
use OpenAPI\Server\MetadataProvider\AnnotationFactory;
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
            'log'                        => [
                LoggerInterface::class => [
                    'writers' => [
                        [
                            'name'    => Messages::class,
                            'options' => [
                                'filters' => [
                                    [
                                        'name'    => 'priority',
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
            'dependencies'               => [
                'aliases'            => [
                    RouterInterface::class => FastRoute::class,
                ],
                'invokables'         => [
                    ArticusPluginManager::class => ArticusPluginManager::class,
                ],
                'factories'          => [
                    FastRoute::class           => RouteInjectionFactory::class,
                    DataTransferService::class => DataTransferServiceFactory::class,
                    InternalServerError::class => ConfigAbstractFactory::class,
                    Annotation::class          => AnnotationFactory::class,
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
            DataTransferService::class   => [
                'metadata_cache' => [
                    'adapter' => [
                        'name' => AnnotationFactory::CACHE_ADAPTER
                    ]
                ],
                'strategies'     => [
                    'invokables' => [
                        Strategy\Date::class                => Strategy\Date::class,
                        Strategy\DateTime::class            => Strategy\DateTime::class,
                        Strategy\QueryParameter::class      => Strategy\QueryParameter::class,
                        Strategy\QueryParameterArray::class => Strategy\QueryParameterArray::class,
                    ],
                    'aliases'    => [
                        'Date'                => Strategy\Date::class,
                        'DateTime'            => Strategy\DateTime::class,
                        'QueryParameter'      => Strategy\QueryParameter::class,
                        'QueryParameterArray' => Strategy\QueryParameterArray::class,
                    ]
                ],
                'validators'     => [
                    'invokables' => [
                        Validator\Type::class                    => Validator\Type::class,
                        Validator\Enum::class                    => Validator\Enum::class,
                        Validator\QueryParameterType::class      => Validator\QueryParameterType::class,
                        Validator\QueryParameterArrayType::class => Validator\QueryParameterArrayType::class,
                    ],
                    'factories'  => [
                        Dictionary::class => ValidatorFactory::class,
                        Collection::class => ValidatorFactory::class,
                    ],
                    'aliases'    => [
                        'Dictionary'              => Dictionary::class,
                        'Collection'              => Collection::class,
                        'Type'                    => Validator\Type::class,
                        'Enum'                    => Validator\Enum::class,
                        'QueryParameterType'      => Validator\QueryParameterType::class,
                        'QueryParameterArrayType' => Validator\QueryParameterArrayType::class,
                    ]
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
                'metadata'  => Annotation::class,
                'handlers'  => ArticusPluginManager::class
            ],
        ];
    }
}
