<?php
declare(strict_types=1);


namespace Task\OpenAPI\V1\Server\Handler;

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
 * @PHA\Route(pattern="/FileSummary/{id}")
 */
class FileSummaryId extends AbstractHandler
{
    /**
     * ATTENTION! REST_OBJECT should be declared in service manager
     */
    const REST_OBJECT = \Task\OpenAPI\V1\Server\Rest\FileSummary::class;

    /**
     * FileSummaryId constructor.
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
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Task\OpenAPI\V1\DTO\DeleteResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function fileSummaryIdDelete(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Delete()');
    }
    /**
     * @PHA\Get()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Task\OpenAPI\V1\DTO\TaskInfoResult::class})
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    public function fileSummaryIdGet(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Get()');
    }
}
