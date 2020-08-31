<?php
declare(strict_types=1);

namespace rollun\Callables\Task;

/**
 * Interface TaskInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface TaskInterface
{
    /**
     * Run task
     *
     * @param object $task
     *
     * @return ResultInterface
     */
    public function runTask(object $task): ResultInterface;
}