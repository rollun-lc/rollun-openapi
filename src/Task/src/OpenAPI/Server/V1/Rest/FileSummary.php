<?php
declare(strict_types=1);

namespace Task\OpenAPI\Server\V1\Rest;

use OpenAPI\Server\Rest\RestInterface;
use OpenAPI\Server\Rest\Traits;
use rollun\Callables\Task\ResultInterface;
use rollun\Callables\TaskExample;

/**
 * Class FileSummary
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class FileSummary extends TaskExample\FileSummary implements RestInterface
{
    use Traits\NoPatch;
    use Traits\NoDelete;
    use Traits\NoGet;
    use Traits\NoPutById;
    use Traits\NoPatchById;

    /**
     * @inheritDoc
     *
     * @throws \Exception
     */
    public function post($bodyData): ResultInterface
    {
        return $this->runTask($bodyData);
    }

    /**
     * @inheritDoc
     */
    public function getById($id): ResultInterface
    {
        return $this->getTaskInfoById($id);
    }
}
