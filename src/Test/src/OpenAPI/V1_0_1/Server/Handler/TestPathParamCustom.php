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
 * @PHA\Route(pattern="/test/{pathParam}/custom")
 */
class TestPathParamCustom extends AbstractHandler
{
    /**
     * ATTENTION! REST_OBJECT should be declared in service manager
     */
    public const REST_OBJECT = \Test\OpenAPI\V1_0_1\Server\Rest\Test::class;

    /**
     * TestPathParamCustom constructor.
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
     *     "type":\Test\OpenAPI\V1_0_1\DTO\TestPathParamCustomGETQueryData::class,
     *     "objectAttr":"queryData",
     *     "source": PHAttribute\Transfer::SOURCE_GET
     * })
     * @PHA\Producer(name=Transfer::class, mediaType="text/plain", options={"responseType":\Test\OpenAPI\V1_0_1\DTO\TestCustomResponse::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function testPathParamCustomGet(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Get()', 'testPathParamCustomGet');
    }
}
