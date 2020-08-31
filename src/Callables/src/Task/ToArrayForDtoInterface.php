<?php
declare(strict_types=1);

namespace rollun\Callables\Task;

/**
 * Interface ToArrayForDtoInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface ToArrayForDtoInterface
{
    /**
     * @return array
     */
    public function toArrayForDto(): array;
}
