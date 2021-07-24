<?php


namespace rollun\test\OpenAPI\unit\Client\Factories;


use GuzzleHttp\Client;
use OpenAPI\Client\Factory\ApiAbstractFactory;
use OpenAPI\Client\Factory\RestAbstractFactory;
use rollun\utils\Factory\AbstractServiceAbstractFactory;
use Test\OpenAPI\V1_0_1\Client\Api\TestApi;
use Test\OpenAPI\V1_0_1\Client\Rest\Test;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

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
}