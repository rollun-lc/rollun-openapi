<?php


namespace OpenAPI\Config;


use Articus\DataTransfer\ClassMetadataProviderInterface;
use Articus\DataTransfer\Factory as DataTransferServiceFactory;
use Articus\DataTransfer\FieldMetadataProviderInterface;
use Articus\DataTransfer\Service as DataTransferService;
use Articus\DataTransfer\Strategy\Factory\PluginManager as StrategyPluginManagerFactory;
use Articus\DataTransfer\Strategy\PluginManager as StrategyPluginManager;
use Articus\DataTransfer\Validator\Collection;
use Articus\DataTransfer\Validator\Factory as ValidatorFactory;
use Articus\DataTransfer\Validator\Factory\PluginManager as ValidatorPluginManagerFactory;
use Articus\DataTransfer\Validator\PluginManager as ValidatorPluginManager;
use Articus\DataTransfer\Validator\TypeCompliant;
use OpenAPI\DataTransfer\MetadataProvider\Annotation;
use OpenAPI\DataTransfer\MetadataProvider\Factory\AnnotationFactory;
use OpenAPI\DataTransfer\Strategy\Factory\FieldDataFactory;
use OpenAPI\DataTransfer\Strategy\FieldData;
use OpenAPI\Server\Strategy;

class DataTransferConfig
{
    public static function getConfig()
    {
        return [
            'dependencies' => [
                'factories' => [
                    DataTransferService::class => DataTransferServiceFactory::class,
                    Annotation::class => AnnotationFactory::class,
                    StrategyPluginManager::class => StrategyPluginManagerFactory::class,
                    ValidatorPluginManager::class => ValidatorPluginManagerFactory::class,
                ],
                'aliases' => [
                    ClassMetadataProviderInterface::class => Annotation::class,
                    FieldMetadataProviderInterface::class => Annotation::class
                ],
            ],
            Annotation::class => [
                'cache' => [
                    'adapter' => 'blackhole'
                ],
            ],
            StrategyPluginManager::class => [
                'factories' => [
                    FieldData::class => FieldDataFactory::class,
                ],
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
        ];
    }
}