<?php


namespace rollun\test\OpenAPI\functional\Openapi;


use OpenAPI\Client\Rest\ClientInterface;
use PHPUnit\Framework\TestCase;

class OpenapiTest extends TestCase
{
    protected static $pid;

    protected static $container;

    protected const MANIFEST = 'test.yaml';

    public static function setUpBeforeClass()
    {
        global $container;
        self::$container = $container;
    }

    public function testGenerateServer()
    {
        $command = 'php bin/openapi-generator generate:server --manifest=' . self::MANIFEST;
        exec($command, $outputs);

        sleep(1);

        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/DTO/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Server/Handler/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Test.php');
    }

    /**
     * @depends testGenerateServer
     */
    public function testGenerateClient()
    {
        $command = 'php bin/openapi-generator generate:client  --debug=true --manifest=' . self::MANIFEST;
        exec($command);

        sleep(1);

        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Client/Api/TestApi.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Client/Rest/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Client/Configuration.php');
    }

    /**
     * @depends testGenerateClient
     */
    public function testGetById()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = $this->getClientClass($clientClass);
        $response = $client->getById(1);
        $this->assertInstanceOf($dtoClass, $response);
    }

    /**
     * @depends testGenerateClient
     */
    public function testGet()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\Collection';
        $dtoClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = $this->getClientClass($clientClass);

        $request = [
            'name' => 'Test',
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertIsArray($response->data);
        $this->assertEquals($request['name'], $response->data[0]->name);
    }

    /**
     * @depends testGenerateClient
     */
    public function testPost()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = $this->getClientClass($clientClass);

        $response = $client->post([
            'id' => '12345',
            'name' => 'Test',
        ]);
        $this->assertInstanceOf($dtoClass, $response);
    }

    /**
     * @depends testGenerateClient
     */
    public function testPostWithoutBody()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';

        $client = $this->getClientClass($clientClass);
        $response = $client->post();

        $this->assertNull($response);
    }

    /**
     * @depends testGenerateClient
     */
    public function testPostWithValidDtoParam()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = $this->getClientClass($clientClass);

        $request = new $dtoClass();
        $request->id = '12345';
        $request->name = 'Test';
        $response = $client->post($request);
        $this->assertInstanceOf($dtoClass, $response);
    }

    /**
     * @depends testGenerateClient
     */
    public function testPostWithInvalidDtoParam()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = $this->getClientClass($clientClass);

        $request = new $dtoClass();
        $request->name = 'Test';

        $this->expectExceptionMessageRegExp('/objectInvalidInner/');
        $this->expectExceptionMessageRegExp('/Value should not be null./');

        $client->post($request);
    }

    /**
     * @depends testGenerateClient
     */
    public function testGetWithDtoResponse()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\BlaResult';

        $client = $this->getClientClass($clientClass);

        $request = [
            'name' => 'OK',
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertIsArray($response->data);
        $this->assertNotEmpty($response->data);
    }

    /**
     * @depends testGenerateClient
     */
    public function testGetWithValidationError()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\BlaResult';

        $client = $this->getClientClass($clientClass);

        $request = [
            'name' => 'Error',
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertNull($response->data);
        $this->assertNotEmpty($response->messages);
        $this->assertEquals('name => Value should not be null.', $response->messages[0]->text);
    }

    /**
     * @depends testGenerateClient
     */
    public function testGetWithException()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\BlaResult';

        $client = $this->getClientClass($clientClass);

        $request = [
            'name' => 'Exception',
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertNull($response->data);
        $this->assertNotEmpty($response->messages);
        $this->assertEquals('Test exception', $response->messages[0]->text);
    }

    public function testTrueExplodeArrayQueryParam()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\Collection';

        $client = $this->getClientClass($clientClass);

        $request = [
            'id' => [
                '1', '2', '3'
            ],
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertIsArray($response->data);
        $this->assertEquals(3, count($response->data));
    }

    public function testCustomGetWithoutOperation()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $client = $this->getClientClass($clientClass);
        $pathParam = 'testPathParam';
        $queryParam = 'testQueryParam';

        $response = $client->testPathParamCustomGet($pathParam, $queryParam);

        $this->assertEquals($pathParam, $response->data->pathParam);
        $this->assertEquals($queryParam, $response->data->queryParam);
    }

    public function testCustomGetWithOperation()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $client = $this->getClientClass($clientClass);
        $pathParam = 'testPathParam';
        $queryParam = 'testQueryParam';

        $response = $client->customOperationGet($pathParam, $queryParam);

        $this->assertEquals($pathParam, $response->data->pathParam);
        $this->assertEquals($queryParam, $response->data->queryParam);
    }

    public function testCustomPostWithoutOperation()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';
        $pathParam = 'testPathParam';

        $client = $this->getClientClass($clientClass);

        $request = new $dtoClass();
        $request->id = '12345';
        $request->name = 'Test';

        $response = $client->testPathParamCustomPost($pathParam,$request);
        $this->assertInstanceOf($dtoClass, $response);
    }

    public function testCustomPostWithOperation()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';
        $pathParam = 'testPathParam';

        $client = $this->getClientClass($clientClass);

        $request = new $dtoClass();
        $request->id = '12345';
        $request->name = 'Test';

        $response = $client->customOperationPost($pathParam,$request);
        $this->assertInstanceOf($dtoClass, $response);
    }

    public function testFalseExplodeArrayQueryParam()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\BlaResult';

        $client = $this->getClientClass($clientClass);

        $request = [
            'id' => [
                '1', '3'
            ],
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertIsArray($response->data);
        $this->assertEquals(2, count($response->data));
    }

    public function testFalseExplodeStringQueryParam()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\BlaResult';

        $client = $this->getClientClass($clientClass);

        $request = [
            'id' => implode(',', [
                '1', '3'
            ]),
        ];
        $response = $client->get($request);
        $this->assertInstanceOf($collectionClass, $response);
        $this->assertIsArray($response->data);
        $this->assertEquals(2, count($response->data));
    }

    private function getClientClass(string $clientClass): ClientInterface
    {
        /** @var ClientInterface $client */
        $client = self::$container->get($clientClass);
        $client->setHostIndex(getenv('TEST_MANIFEST_HOST_INDEX') === false ? 0 : (int)getenv('TEST_MANIFEST_HOST_INDEX'));
        return $client;
    }
}
