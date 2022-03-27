<?php


namespace rollun\test\OpenAPI\functional\Openapi;


use PHPUnit\Framework\TestCase;

class OpenapiTest extends TestCase
{
    protected static $php;

    protected static $pid;

    protected static $container;

    protected const MANIFEST = 'test.yaml';
    //protected const MANIFEST = 'https://raw.githubusercontent.com/rollun-com/openapi-manifests/ab8c5b5c3e6364be207473c17bbc647d62bf07d7/test__v1.0.1.yml';

    public static function setUpBeforeClass(): void
    {
        self::$php = PHP_BINARY;
        global $container;
        self::$container = $container;
        self::$pid = exec(self::$php . ' -S localhost:8001 public/index.php 1>/dev/null & echo $!');
    }

    public static function tearDownAfterClass(): void
    {
        exec('kill -9 ' . self::$pid);
        //exec('rm -rf src/Test');
        //exec('rm -rf public/openapi/docs/Test');
        //unlink('config/autoload/test_v1_0_1_path_handler.global.php');
    }

    public function testGenerateServer()
    {
        $command = self::$php . ' bin/openapi-generator generate:server --manifest=' . self::MANIFEST;
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
        $command = self::$php . ' bin/openapi-generator generate:client  --debug=true --manifest=' . self::MANIFEST;
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

        $client = self::$container->get($clientClass);
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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);
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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

        $request = new $dtoClass();
        $request->name = 'Test';

        $this->expectExceptionMessageMatches('/objectInvalidInner/');
        $this->expectExceptionMessageMatches('/Value should not be null./');

        $client->post($request);
    }

    /**
     * @depends testGenerateClient
     */
    public function testGetWithDtoResponse()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';
        $collectionClass = '\\Test\\OpenAPI\\V1_0_1\\DTO\\BlaResult';

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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
        $client = self::$container->get($clientClass);
        $pathParam = 'testPathParam';
        $queryParam = 'testQueryParam';

        $response = $client->testPathParamCustomGet($pathParam, $queryParam);

        $this->assertEquals($pathParam, $response->data->pathParam);
        $this->assertEquals($queryParam, $response->data->queryParam);
    }

    public function testCustomGetWithOperation()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $client = self::$container->get($clientClass);
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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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

        $client = self::$container->get($clientClass);

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
}