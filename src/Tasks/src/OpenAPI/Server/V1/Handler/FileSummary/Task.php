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
use Psr\Log\LoggerInterface;
use rollun\Callables\TaskExample\FileSummary;
use rollun\Callables\TaskExample\Model\CreateTaskParameters;
use Articus\DataTransfer\Service as DTService;
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
     * @param DTService $dt
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
     * @PHA\Attribute(name=PHAttribute\Transfer::class, options={"type":\Tasks\OpenAPI\Server\V1\DTO\CreateTaskParameters::class,"objectAttr":"bodyData"})
     * @PHA\Producer(name=Transfer::class, mediaType="application/json", options={"responseType":\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult::class})
     *
     * @param ServerRequestInterface $request
     *
     * @return array|\Tasks\OpenAPI\Server\V1\DTO\TaskInfoResult
     * @throws PHException\HttpCode 501 if the method is not implemented
     *
     */
    public function runTask(ServerRequestInterface $request)
    {
        /** @var \Tasks\OpenAPI\Server\V1\DTO\CreateTaskParameters $bodyData */
        $bodyData = $request->getAttribute("bodyData");

//        $result = (new FileSummary())->runTask(new CreateTaskParameters($bodyData->n));

        $result = [
            'data'     => [
                'id'         => '2',
                'type'       => 'FileSummary',
                'type3'      => 'FileSummary',
                'timeout'    => 3,
                'stage'      => [
                    'stage' => 'done',
                    'all'   => ['writing 1', 'writing 2', 'summary calculating', 'done'],
                ],
                'status'     => [
                    'state' => 'fulfilled',
                    'all'   => ['pending', 'rejected', 'fulfilled']
                ],
                'result'     => [
                    'data'     => [
                        'summary' => 3
                    ],
                    'messages' => []
                ],
                'startTime2' => null
            ],
            'messages' => []
        ];

//
//        echo '<pre>';
//        print_r($errors);
//        die();
//
//        echo '<pre>';
//        print_r(new FileSummary());
//        die();

//        return (new FileSummary())->runTask(new CreateTaskParameters($bodyData->n));
////
////        echo '<pre>';
////        print_r($result);
////        die();
//
        return $result;
    }
}
