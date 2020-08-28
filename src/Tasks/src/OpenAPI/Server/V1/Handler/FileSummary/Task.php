<?php
declare(strict_types=1);


namespace Tasks\OpenAPI\Server\V1\Handler\FileSummary;

use Articus\PathHandler\Annotation as PHA;
use Articus\PathHandler\Consumer as PHConsumer;
use Articus\PathHandler\Producer as PHProducer;
use Articus\PathHandler\Attribute as PHAttribute;
use Articus\PathHandler\Exception as PHException;
use OpenAPI\Server\Handler\AbstractHandler;
use OpenAPI\Server\Producer\Transfer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @PHA\Route(pattern="/task")
 */
class Task extends AbstractHandler
{
    /**
     * Create task
     * @PHA\Post()
     * TODO check if consumer is valid, if it has correct priority and if it can be moved to class annotation
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaType="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\Tasks\OpenAPI\Server\V1\DTO\UNKNOWN_BASE_TYPE::class,"objectAttr":"bodyData"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult::class})
     * @param ServerRequestInterface $request
     *
     * @throws PHException\HttpCode 501 if the method is not implemented
     *
     * @return array|\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult
     */
    public function runTask(ServerRequestInterface $request)
    {
        //TODO implement method
        /** @var \Tasks\OpenAPI\Server\V1\DTO\UNKNOWN_BASE_TYPE $bodyData */
        $bodyData = $request->getAttribute("bodyData");
        throw new PHException\HttpCode(501, "Not implemented");
    }
}
