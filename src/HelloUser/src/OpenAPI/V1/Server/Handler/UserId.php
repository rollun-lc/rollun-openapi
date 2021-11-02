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
 * @PHA\Route(pattern="/User/{id}")
 */
class UserId extends AbstractHandler
{
    /**
     * UserId constructor.
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
     * @PHA\Get()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\HelloUser\OpenAPI\V1\DTO\UserResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function userIdGet(ServerRequestInterface $request)
    {
        return $this->runAction($request, 'Get()');
    }
}
