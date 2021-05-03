<?php


namespace rollun\test\OpenAPI\Unit\Openapi;


use PHPUnit\Framework\TestCase;
/*use Test\OpenAPI\V1_0_1\Server\Handler\Test;
use Test\OpenAPI\V1_0_1\Server\Handler\TestId;*/

use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;
use Test\OpenAPI\V1_0_1\Server\Rest\Test;
use Zend\ServiceManager\ServiceManager;

class OpenapiTest extends TestCase
{
    protected static $pid;

    protected static $container;

    public static function setUpBeforeClass()
    {
        global $container;
        self::$container = $container;
        //self::$pid = exec('php -S localhost:8000 1>/dev/null & echo $!');
    }

    public static function tearDownAfterClass()
    {
        //exec('kill -9 ' . self::$pid);
        //exec('rm -rf src/Test');
        //unlink('config/autoload/test_v1_0_1_path_handler.global.php');
    }

    /*public function testGenerateServer()
    {
        $command = 'php bin/openapi-generator generate:server --manifest=test.yaml';
        exec($command, $outputs);

        sleep(1);

        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/DTO/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Server/Handler/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Server/Rest/Test.php');
    }*/

    /**
     * @dependss testGenerateServer
     */
    /*public function testGenerateClient()
    {
        $command = 'php bin/openapi-generator generate:client --manifest=test.yaml --debug=true';
        exec($command);

        sleep(1);

        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Client/Api/TestApi.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Client/Rest/Test.php');
        $this->assertFileExists('src/Test/src/OpenAPI/V1_0_1/Client/Configuration.php');
    }*/

    /**
     * @dependss testGenerateClient
     */
    public function testInteraction()
    {


        $config = self::$container->get('config');
        $config['dependencies']['invokables'][ControllerObject::class] = ControllerObject::class;
        $container = new ServiceManager();
        $container->configure($config['dependencies']);
        $container->setService('config', $config);

        //$controllerObject = $container->get(ControllerObject::class);
        InsideConstruct::setContainer($container);
        $server = new ServerRest(new ControllerObject());

        $server = new class() extends Test {
            const CONTROLLER_OBJECT = ControllerObject::class;
        };
        InsideConstruct::setContainer(self::$container);

        $container->get(get_class($testHandler));

        $clientClass = '\\Test\\OpenAPI\\V1_0_1\\Client\\Rest\\Test';
        $dtoClass = '\Test\\OpenAPI\\V1_0_1\\DTO\\Test';

        /*$test = new $dtoClass();
        $test->id = '12345';
        $test->name = 'Test';*/

        $client = self::$container->get($clientClass);
        /*$response = $client->post([
            'id' => '12345',
            'name' => 'Test',
        ]);*/

    }
}