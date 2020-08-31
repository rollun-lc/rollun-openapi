<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Result;

use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 * Interface MessageInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface MessageInterface extends ToArrayForDtoInterface
{
    /**
     * Get level of message
     *
     * @return string
     */
    public function getLevel(): string;

    /**
     * Get message text
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Get message context (like in a logger)
     *
     * @return array|null
     */
    public function getContext(): ?array;
}
