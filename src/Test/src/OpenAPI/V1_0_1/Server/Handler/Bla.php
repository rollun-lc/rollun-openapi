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
 * @PHA\Route(pattern="/Bla")
 */
class Bla extends AbstractHandler
{
    /**
     * ATTENTION! REST_OBJECT should be declared in service manager
     */
    public const REST_OBJECT = \Test\OpenAPI\V1_0_1\Server\Rest\Bla::class;

    /**
     * Bla constructor.
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
     *     "type":\Test\OpenAPI\V1_0_1\DTO\BlaGETQueryData::class,
     *     "objectAttr":"queryData",
     *     "source": PHAttribute\Transfer::SOURCE_GET
     * })
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Test\OpenAPI\V1_0_1\DTO\BlaResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function blaGet(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Get()');
    }
    /**
     * @PHA\Post()
     * @param ServerRequestInterface $request
     */
    public function blaPost(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Post()');
    }
}
