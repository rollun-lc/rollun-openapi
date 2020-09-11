<?php
declare(strict_types=1);

namespace Task\OpenAPI\Server\V1\Rest;

use OpenAPI\Server\Rest\BaseAbstract;
use OpenAPI\Server\Rest\RestInterface;
use rollun\Callables\TaskExample;
use rollun\dic\InsideConstruct;

/**
 * Class FileSummary
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class FileSummary extends BaseAbstract implements RestInterface
{
    const CONTROLLER_OBJECT = TaskExample\FileSummary::class;

    /**
     * @var TaskExample\FileSummary
     */
    protected $controllerObject;

    /**
     * FileSummary constructor.
     *
     * @param TaskExample\FileSummary|null $controllerObject
     *
     * @throws \ReflectionException
     */
    public function __construct(TaskExample\FileSummary $controllerObject = null)
    {
        InsideConstruct::init(['controllerObject' => self::CONTROLLER_OBJECT]);
    }

    /**
     * @inheritDoc
     *
     * @throws \Exception
     */
    public function post($bodyData)
    {
        return $this->controllerObject->runTask($bodyData);
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        return $this->controllerObject->getTaskInfoById($id);
    }
}
