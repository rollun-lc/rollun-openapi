<?php
declare(strict_types=1);


namespace HelloUser\OpenAPI\Server\V1\Handler;

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
 * @PHA\Route(pattern="/User")
 */
class User extends AbstractHandler
{
    const REST_OBJECT = \HelloUser\OpenAPI\Server\V1\Rest\User::class;

    /**
     * User constructor.
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
     * @PHA\Post()
     * TODO check if consumer is valid, if it has correct priority and if it can be moved to class annotation
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaType="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\HelloUser\OpenAPI\Server\V1\DTO\InlineObject::class,"objectAttr":"bodyData"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\HelloUser\OpenAPI\Server\V1\DTO\UserResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userPost(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Post()');
    }
}