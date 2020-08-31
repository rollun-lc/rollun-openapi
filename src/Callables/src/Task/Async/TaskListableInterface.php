<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async;

/**
 * Interface TaskListableInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface TaskListableInterface extends TaskInterface
{
    /**
     * Return exists tasks id
     *
     * @return string[]
     */
    public function getTaskIdList(): array;
}