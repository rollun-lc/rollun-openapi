<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async\Result\Data;

use rollun\Callables\Task\ResultInterface;

/**
 * Class TaskInfo
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class TaskInfo implements TaskInfoInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var StageInterface
     */
    protected $stage;

    /**
     * @var StatusInterface
     */
    protected $status;

    /**
     * @var ResultInterface
     */
    protected $result;

    /**
     * @var \DateTime|null
     */
    protected $startTime;

    /**
     * TaskInfo constructor.
     *
     * @param string          $id
     * @param string          $type
     * @param int             $timeout
     * @param StageInterface  $stage
     * @param StatusInterface $status
     * @param ResultInterface $result
     * @param \DateTime|null  $startTime
     */
    public function __construct(string $id, string $type, int $timeout, StageInterface $stage, StatusInterface $status, ResultInterface $result, \DateTime $startTime = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->timeout = $timeout;
        $this->stage = $stage;
        $this->status = $status;
        $this->result = $result;
        $this->startTime = $startTime;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getTimeout(): int
    {
        $this->timeout;
    }

    /**
     * @inheritDoc
     */
    public function getStage(): StageInterface
    {
        return $this->stage;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): StatusInterface
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $startTime
     */
    public function setStartTime(\DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @inheritDoc
     */
    public function getResult(): ResultInterface
    {
        return $this->result;
    }
}
