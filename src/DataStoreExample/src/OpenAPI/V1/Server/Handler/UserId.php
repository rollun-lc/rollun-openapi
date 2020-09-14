<?php
declare(strict_types=1);


namespace DataStoreExample\OpenAPI\V1\Server\Handler;

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
 * @PHA\Route(pattern="/User/{id}")
 */
class UserId extends AbstractHandler
{
    /**
     * ATTENTION! REST_OBJECT should be declared in service manager
     */
    const REST_OBJECT = \DataStoreExample\OpenAPI\V1\Server\Rest\User::class;

    /**
     * UserId constructor.
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
     * @PHA\Delete()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\DataStoreExample\OpenAPI\V1\DTO\Result::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userIdDelete(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Delete()');
    }
    /**
     * @PHA\Get()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\DataStoreExample\OpenAPI\V1\DTO\UserResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userIdGet(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Get()');
    }
    /**
     * @PHA\Patch()
     * TODO check if consumer is valid, if it has correct priority and if it can be moved to class annotation
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaType="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\DataStoreExample\OpenAPI\V1\DTO\User::class,"objectAttr":"bodyData", "errorAttr":"errors"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\DataStoreExample\OpenAPI\V1\DTO\UserResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userIdPatch(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Patch()');
    }
    /**
     * @PHA\Put()
     * TODO check if consumer is valid, if it has correct priority and if it can be moved to class annotation
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaType="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\DataStoreExample\OpenAPI\V1\DTO\PutUser::class,"objectAttr":"bodyData", "errorAttr":"errors"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\DataStoreExample\OpenAPI\V1\DTO\UserResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userIdPut(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Put()');
    }
}
