<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample;

use OpenAPI\Server\Rest\Base;
use rollun\dic\InsideConstruct;

/**
 * Class FileSummaryRest
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class FileSummaryRest extends Base
{
    /**
     * @var FileSummary
     */
    protected $taskObject;

    /**
     * FileSummaryRest constructor.
     *
     * @param FileSummary|null $taskObject
     *
     * @throws \ReflectionException
     */
    public function __construct(FileSummary $taskObject = null)
    {
        InsideConstruct::init(['taskObject' => FileSummary::class]);
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
        InsideConstruct::initWakeup(['taskObject' => FileSummary::class]);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function post($bodyData): array
    {
        return $this->taskObject->runTask($bodyData)->toArrayForDto();
    }

    /**
     * @inheritDoc
     */
    public function getById($id): array
    {
        return $this->taskObject->getTaskInfoById((string)$id)->toArrayForDto();
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id): array
    {
        return $this->taskObject->deleteById((string)$id)->toArrayForDto();
    }
}
