<?php

namespace Task\OpenAPI\V1\Server\Rest;

use OpenAPI\Server\Rest\BaseAbstract;
use Psr\Log\LoggerInterface;
use rollun\Callables\TaskExample;
use rollun\dic\InsideConstruct;

/**
 * Class FileSummary
 */
class FileSummary extends BaseAbstract
{
    const CONTROLLER_OBJECT = TaskExample\FileSummary::class;

    /** @var TaskExample\FileSummary */
    protected $controllerObject;

    /** @var LoggerInterface */
    protected $logger;


    /**
     * FileSummary constructor.
     *
     * @param mixed $controllerObject
     * @param LoggerInterface|null logger
     *
     * @throws \ReflectionException
     */
    public function __construct($controllerObject = null, $logger = null)
    {
        InsideConstruct::init(['controllerObject' => self::CONTROLLER_OBJECT, 'logger' => LoggerInterface::class]);
    }


    /**
     * @inheritDoc
     *
     * @param \Task\OpenAPI\V1\DTO\PostFileSummary $bodyData
     */
    public function post($bodyData)
    {
        return $this->controllerObject->runTask($bodyData)->toArrayForDto();
    }


    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        return $this->controllerObject->deleteById($id)->toArrayForDto();
    }


    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        return $this->controllerObject->getTaskInfoById($id)->toArrayForDto();
    }
}
