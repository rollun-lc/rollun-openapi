<?php
declare(strict_types=1);


namespace HelloUser\OpenAPI\V1\Server\Handler;

use Articus\PathHandler\Annotation as PHA;
use Articus\PathHandler\Consumer as PHConsumer;
use Articus\PathHandler\Producer as PHProducer;
use Articus\PathHandler\Attribute as PHAttribute;
use Articus\PathHandler\Exception as PHException;
use HelloUser\OpenAPI\V1\Server\Rest\UserInterface;
use OpenAPI\Server\Handler\AbstractHandler;
use Psr\Http\Message\ServerRequestInterface;
use rollun\dic\InsideConstruct;

/**
 * @PHA\Route(pattern="/User")
 */
class User extends AbstractHandler
{
    /**
     * User constructor.
     *
     * @param UserInterface|null $restObject
     *
     * @throws \ReflectionException
     */
    public function __construct(UserInterface $restObject = null)
    {
        InsideConstruct::init(['restObject' => UserInterface::class]);
    }

    /**
     * @PHA\Post()
     * TODO check if consumer is valid, if it has correct priority and if it can be moved to class annotation
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaType="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\HelloUser\OpenAPI\V1\DTO\User::class,"objectAttr":"bodyData", "errorAttr":"errors"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\HelloUser\OpenAPI\V1\DTO\UserResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userPost(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Post()');
    }
}
