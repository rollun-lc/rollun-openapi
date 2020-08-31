<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async;

use rollun\Callables\Task\TaskInterface as SyncTaskInterface;
use rollun\Callables\Task\Result\TaskInfoInterface;
use rollun\Callables\Task\ResultInterface;

/**
 * Interface TaskInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface TaskInterface extends SyncTaskInterface
{
    /**
     * Return concreted task info by id
     *
     * @param string $taskId
     *
     * @return TaskInfoInterface
     */
    public function getTaskInfoById(string $taskId): TaskInfoInterface;

    /**
     * Create new task
     *
     * @param object $taskParam
     *
     * @return TaskInfoInterface
     */
    public function runTask(object $taskParam): ResultInterface;

    /**
     * Delete task
     *
     * @param string $id
     *
     * @return ResultInterface
     */
    public function deleteById(string $id): ResultInterface;
}