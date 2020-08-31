<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async\Result\Data;

use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 * Interface StageInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface StageInterface extends ToArrayForDtoInterface
{
    /**
     * Get current stage
     *
     * @return string
     */
    public function getStage(): string;

    /**
     * Get all available stages
     *
     * @return array
     */
    public function getAllStages(): array;

    /**
     * Get current stage
     *
     * @return string
     */
    public function __toString(): string;
}
