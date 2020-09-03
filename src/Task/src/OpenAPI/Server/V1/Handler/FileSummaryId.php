<?php
declare(strict_types=1);


namespace Task\OpenAPI\Server\V1\Handler;

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
     * FileSummaryId constructor.
     *
     * @param RestInterface|null $restObject
     *
     * @throws \ReflectionException
     */
    public function __construct(RestInterface $restObject = null)
    {
        InsideConstruct::init(['restObject' => 'FileSummary']);
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
       InsideConstruct::initWakeup(['restObject' => 'FileSummary']);
    }

     /**
      * @return array
      */
     public function __sleep()
     {
        return [];
     }

    /**
     * @PHA\Delete()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Task\OpenAPI\Server\V1\DTO\DeleteResult::class})
     * @param ServerRequestInterface $request
     *
     * @throws PHException\HttpCode 501 if the method is not implemented
     *
     * @return array
     */
    public function fileSummaryIdDelete(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Delete()');
    }
    /**
     * @PHA\Get()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Task\OpenAPI\Server\V1\DTO\TaskInfoResult::class})
     * @param ServerRequestInterface $request
     *
     * @throws PHException\HttpCode 501 if the method is not implemented
     *
     * @return array
     */
    public function fileSummaryIdGet(ServerRequestInterface $request): array
    {
        return $this->runAction($request, 'Get()');
    }
}
