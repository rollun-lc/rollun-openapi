<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Result;

use rollun\Callables\Task\ResultInterface;
use rollun\Callables\Task\Async\Result\Data\TaskInfoInterface as DataTaskInfoInterface;

/**
 * Interface TaskInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface TaskInfoInterface extends ResultInterface
{
    /**
     * @return DataTaskInfoInterface
     */
    public function getData();
}
