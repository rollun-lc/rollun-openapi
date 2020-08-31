<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async\Result\Data;

/**
 * Class Status
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Status implements StatusInterface
{
    /**
     * @var string
     */
    protected $state;

    /**
     * @var string[]
     */
    protected $all;

    /**
     * Status constructor.
     */
    public function __construct()
    {
        $this->state = StatusInterface::STATE_PENDING;
        $this->all = [StatusInterface::STATE_PENDING, StatusInterface::STATE_REJECTED, StatusInterface::STATE_FULFILLED];
    }

    /**
     * @inheritDoc
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @inheritDoc
     */
    public function getAllStates(): array
    {
        return $this->all;
    }

    /**
     * @inheritDoc
     */
    public function isPending(): bool
    {
        return $this->getState() === StatusInterface::STATE_PENDING;
    }

    /**
     * @inheritDoc
     */
    public function isRejected(): bool
    {
        return $this->getState() === StatusInterface::STATE_REJECTED;
    }

    /**
     * @inheritDoc
     */
    public function toReject(): void
    {
        $this->state = StatusInterface::STATE_REJECTED;
    }

    /**
     * @inheritDoc
     */
    public function isFulfilled(): bool
    {
        return $this->getState() === StatusInterface::STATE_FULFILLED;
    }

    /**
     * @inheritDoc
     */
    public function toFulfilled(): void
    {
        $this->state = StatusInterface::STATE_FULFILLED;
    }

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        return [
            'state' => $this->getState(),
            'all'   => $this->getAllStates(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getState();
    }
}
