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
 * @PHA\Route(pattern="/task/{id}")
 */
class TaskId extends AbstractHandler
{
    /**
     * Delete task
     * @PHA\Delete()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\DeleteResult::class})
     * @param ServerRequestInterface $request
     *
     * @throws PHException\HttpCode 501 if the method is not implemented
     *
     * @return array|\Tasks\OpenAPI\Server\V1\DTO\DeleteResult
     */
    public function deleteById(ServerRequestInterface $request)
    {
        //TODO implement method
        throw new PHException\HttpCode(501, "Not implemented");
    }
    /**
     * Return concreted task info by id
     * @PHA\Get()
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult::class})
     * @param ServerRequestInterface $request
     *
     * @throws PHException\HttpCode 501 if the method is not implemented
     *
     * @return array|\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult
     */
    public function getTaskInfoById(ServerRequestInterface $request)
    {
        //TODO implement method
        throw new PHException\HttpCode(501, "Not implemented");
    }
}
