<?php
declare(strict_types=1);


namespace Test\OpenAPI\V1_0_1\Server\Handler;

use Articus\PathHandler\Annotation as PHA;
use Articus\PathHandler\Consumer as PHConsumer;
use Articus\PathHandler\Producer as PHProducer;
use Articus\PathHandler\Attribute as PHAttribute;
use Articus\PathHandler\Exception as PHException;
use OpenAPI\Server\Producer\Transfer;
use OpenAPI\Server\Handler\AbstractHandler;
use OpenAPI\Server\Rest\RestInterface;
use Psr\Http\Message\ServerRequestInterface;
use rollun\dic\InsideConstruct;

/**
 * @PHA\Route(pattern="/Test")
 */
class Test extends AbstractHandler
{
    /**
     * ATTENTION! REST_OBJECT should be declared in service manager
     */
    public const REST_OBJECT = \Test\OpenAPI\V1_0_1\Server\Rest\Test::class;

    /**
     * Test constructor.
     *
     * @param RestInterface|null $restObject
     *
     * @throws \ReflectionException
     */
    public function __construct(RestInterface $restObject = null)
    {
        InsideConstruct::init(['restObject' => self::REST_OBJECT]);
    }

    /**
     * @PHA\Get()
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={
     *     "type":\Test\OpenAPI\V1_0_1\DTO\TestGETQueryData::class,
     *     "objectAttr":"queryData",
     *     "source": PHAttribute\Transfer::SOURCE_GET
     * })
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Test\OpenAPI\V1_0_1\DTO\Collection::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function testGet(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Get()', 'testGet');
    }
    /**
     * @PHA\Post()
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaRange="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\Test\OpenAPI\V1_0_1\DTO\Test::class,"objectAttr":"bodyData", "errorAttr":"errors"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Test\OpenAPI\V1_0_1\DTO\Test::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function testPost(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Post()', 'testPost');
    }
}
