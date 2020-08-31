<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async\Result\Data;

use rollun\Callables\Task\ResultInterface;
use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 * Interface TaskInfoInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface TaskInfoInterface extends ToArrayForDtoInterface
{
    /**
     * Get task id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get task timeout
     *
     * @return int
     */
    public function getTimeout(): int;

    /**
     * Get task current stage
     *
     * @return StageInterface
     */
    public function getStage(): StageInterface;

    /**
     * Get status
     *
     * @return StatusInterface
     */
    public function getStatus(): StatusInterface;

    /**
     * Get datetime when task was start
     *
     * @return \DateTime|null UTC DateTime in result
     */
    public function getStartTime(): ?\DateTime;

    /**
     * Get task result
     *
     * @return ResultInterface
     */
    public function getResult(): ResultInterface;
}
