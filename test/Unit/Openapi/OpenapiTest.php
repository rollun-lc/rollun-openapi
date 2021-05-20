<?php


namespace rollun\test\OpenAPI\Unit\Openapi;


use PHPUnit\Framework\TestCase;

class OpenapiTest extends TestCase
{
    protected static $pid;

    protected static $container;

    protected const MANIFEST = 'test.yaml';
    //protected const MANIFEST = 'https://raw.githubusercontent.com/rollun-com/openapi-manifests/ab8c5b5c3e6364be207473c17bbc647d62bf07d7/test__v1.0.1.yml';

    public static function setUpBeforeClass()
    {
        global $container;
        self::$container = $container;
        self::$pid = exec('php -S localhost:8001 public/index.php 1>/dev/null & echo $!');
    }

    public static function tearDownAfterClass()
    {
        exec('kill -9 ' . self::$pid);
        exec('rm -rf src/Test');
        exec('rm -rf public/openapi/docs/Test');
        unlink('config/autoload/test_v1_0_1_path_handler.global.php');
    }

    public function testGenerateServer()
    {
        $command = 'php bin/openapi-generator generate:server --manifest=' . self::MANIFEST;
        exec($command, $outputs);

        sleep(1);

        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/DTO/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Server/Handler/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Test.php');

        // TODO
        $content = file_get_contents('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Test.php');
        $content = str_replace(
            "'Name of service which implements OpenApi logic'",
            '\\' . TestControllerObject::class . '::class',
            $content
        );
        file_put_contents('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Test.php', $content);

        $content = file_get_contents('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Bla.php');
        $content = str_replace(
            "'Name of service which implements OpenApi logic'",
            '\\' . BlaControllerObject::class . '::class',
            $content
        );
        file_put_contents('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Bla.php', $content);
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
    public function tesGet()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = self::$container->get($clientClass);
        $response = $client->getById(1);
        $this->assertInstanceOf($dtoClass, $response);

        $response = $client->post([
            'id' => '12345',
            'name' => 'Test',
        ]);
        $this->assertInstanceOf($dtoClass, $response);
    }

    /**
     * @dependss testGenerateClient
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
     * @dependss testGenerateClient
     */
    public function testPostWithoutBody()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Bla';

        $client = self::$container->get($clientClass);
        $response = $client->post();

        $this->assertNull($response);
    }

    /*public function testResponseWithError()
    {
        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        $client = self::$container->get($clientClass);
        $response = $client->getById(1);
        $this->assertInstanceOf($dtoClass, $response);

        $response = $client->post([
            'id' => '12345',
            'name' => 'Test',
        ]);
        $this->assertInstanceOf($dtoClass, $response);
    }*/
}