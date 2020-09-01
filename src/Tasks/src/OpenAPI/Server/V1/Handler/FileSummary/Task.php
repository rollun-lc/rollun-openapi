<?php
declare(strict_types=1);

namespace Tasks\OpenAPI\Server\V1\Handler\FileSummary;

use Articus\PathHandler\Annotation as PHA;
use Articus\PathHandler\Consumer as PHConsumer;
use Articus\PathHandler\Attribute as PHAttribute;
use OpenAPI\Server\Handler\AbstractHandler;
use OpenAPI\Server\Producer\Transfer;
use Psr\Http\Message\ServerRequestInterface;
use rollun\Callables\TaskExample\FileSummary;
use rollun\Callables\TaskExample\Model\CreateTaskParameters;
use rollun\dic\InsideConstruct;

/**
 * @PHA\Route(pattern="/task")
 */
class Task extends AbstractHandler
{
    /**
     * @var FileSummary
     */
    protected $fileSummary;

    /**
     * Task constructor.
     *
     * @param FileSummary $fileSummary
     */
    public function __construct(FileSummary $fileSummary = null)
    {
        InsideConstruct::init(['fileSummary' => FileSummary::class]);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
        InsideConstruct::initWakeup(['fileSummary' => FileSummary::class]);
    }

    /**
     * Create task
     * @PHA\Post()
     * @PHA\Consumer(name=PHConsumer\Json::class, mediaType="application/json")
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\Tasks\OpenAPI\Server\V1\DTO\InlineObject::class,"objectAttr":"bodyData"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult::class})
     *
     * @param ServerRequestInterface $request
     *
     * @return array
     * @throws \Exception
     */
    public function runTask(ServerRequestInterface $request)
    {
        /** @var \Tasks\OpenAPI\Server\V1\DTO\InlineObject $bodyData */
        $bodyData = $request->getAttribute("bodyData");

        return $this->fileSummary->runTask(new CreateTaskParameters($bodyData->n))->toArrayForDto();
    }
}
