<?php


namespace rollun\test\OpenAPI\unit\Client\Factories;


use GuzzleHttp\Client;
use OpenAPI\Client\Configuration\AuthenticatorInterface;
use OpenAPI\Client\Factory\ApiAbstractFactory;
use OpenAPI\Client\Factory\ConfigurationAbstractFactory;
use OpenAPI\Client\Factory\RestAbstractFactory;
use Test\OpenAPI\V1_0_1\Client\Api\TestApi;
use Test\OpenAPI\V1_0_1\Client\Rest\Test;

class ClientFactoryTest extends TestAbstract
{
    public function testCanCreate()
    {
        $clientFactory = new RestAbstractFactory();
        $result = $clientFactory->canCreate($this->container, 'TestClient1');

        $this->assertTrue($result);
    }

    public function testInvoke()
    {
        $clientFactory = new RestAbstractFactory();
        $instance = $clientFactory($this->container, 'TestClient1');

        $this->assertInstanceOf(Test::class, $instance);
    }

    public function testConfigureApi()
    {
        $config = array_merge_recursive($this->getDefaultConfig(), [
            ApiAbstractFactory::KEY => [
                'TestClientApi' => [
                    ApiAbstractFactory::KEY_HOST_INDEX => 5,
                ],
            ],
        ]);

        $this->configureContainer($config);

        $clientFactory = new RestAbstractFactory();
        $instance = $clientFactory($this->container, 'TestClient');

        $api = $this->getProperty($instance, 'api');
        $this->assertEquals(5, $api->getHostIndex());
    }

    public function testConfigureApiClient()
    {
        $config = array_merge_recursive($this->getDefaultConfig(), [
            ApiAbstractFactory::KEY => [
                'TestClientApi' => [
                    ApiAbstractFactory::KEY_CLIENT => 'TestHttpClient',
                ]
            ],
            'dependencies' => [
                'factories' => [
                    'TestHttpClient' => function () {
                        return new Client(['timeout' => 480]);
                    }
                ]
            ],
        ]);

        $this->configureContainer($config);

        $clientFactory = new RestAbstractFactory();
        $instance = $clientFactory($this->container, 'TestClient');

        $client = $this->getProperty($instance, 'api.client');
        $this->assertEquals(480, $client->getConfig('timeout'));
    }

    protected function getDefaultConfig()
    {
        return [
            RestAbstractFactory::KEY => [
                'TestClient' => [
                    RestAbstractFactory::KEY_CLASS => Test::class,
                    RestAbstractFactory::KEY_API_NAME => 'TestClientApi'
                ]
            ],
            ApiAbstractFactory::KEY => [
                'TestClientApi' => [
                    ApiAbstractFactory::KEY_CLASS => TestApi::class,
                ]
            ],
        ];
    }

    public function testConfigureDefaultConfiguration()
    {
        $config = array_merge_recursive($this->getDefaultConfig(), [
            ConfigurationAbstractFactory::KEY => [
                \Test\OpenAPI\V1_0_1\Client\Configuration::class => [
                    ConfigurationAbstractFactory::KEY_AUTHENTICATOR => AuthenticatorInterface::class,
                ]
            ],
        ]);
        $this->configureContainer($config);

        $accessToken = uniqid('', true);
        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator->expects($this->any())->method('getAccessToken')->willReturn($accessToken);
        $this->container->setService(AuthenticatorInterface::class, $authenticator);

        $clientFactory = new ApiAbstractFactory();
        $api = $clientFactory($this->container, 'TestClientApi');

        $result = $api->getConfig()->getAccessToken();

        $this->assertEquals($accessToken, $result);
    }
}