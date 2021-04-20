<?php
declare(strict_types=1);

namespace OpenAPI;

use OpenAPI\Client\Factory\ApiInstanceAbstractFactory;
use OpenAPI\Client\Factory\ConfigurationAbstractFactory;
use OpenAPI\Config\DataTransferConfig;
use OpenAPI\Config\PathHandlerConfig;
use OpenAPI\Server\Validator;
use OpenAPI\Server\Writer\Messages;
use Psr\Log\LoggerInterface;
use Zend\Validator\ValidatorPluginManager;
use Zend\Validator\ValidatorPluginManagerFactory;

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
        $config = [
            'dependencies' => [
                'abstract_factories' => [
                    ApiInstanceAbstractFactory::class,
                    ConfigurationAbstractFactory::class,
                ]
            ],
        ];

        $logConfig = $this->getLogConfig();
        $zendValidatorsConfig = $this->getZendValidatorsPluginManagerConfig();
        $dataTransferConfig = DataTransferConfig::getConfig();
        $pathHandlerConfig = PathHandlerConfig::getConfig();

        return array_merge_recursive(
            $logConfig,
            $zendValidatorsConfig,
            $dataTransferConfig,
            $pathHandlerConfig,
            $config
        );
    }

    public function getLogConfig(): array
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
            ]
        ];
    }

    public function getZendValidatorsPluginManagerConfig(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    ValidatorPluginManager::class => ValidatorPluginManagerFactory::class
                ],
            ],
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
        ];
    }
}
