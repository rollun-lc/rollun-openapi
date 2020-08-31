<?php
declare(strict_types=1);

namespace rollun\Callables\Task;

/**
 * Interface ResultInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface ResultInterface extends ErrorResultInterface
{
    /**
     * Get data
     *
     * @return mixed
     */
    public function getData();
}
