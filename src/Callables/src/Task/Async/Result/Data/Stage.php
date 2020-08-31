<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Async\Result\Data;

/**
 * Class Stage
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Stage implements StageInterface
{
    /**
     * @var string
     */
    protected $stage;

    /**
     * @var string[]
     */
    protected $all;

    /**
     * Stage constructor.
     *
     * @param string[] $all
     * @param string   $stage
     */
    public function __construct(array $all, string $stage)
    {
        $this->all = $all;

        $this->setStage($stage);
    }

    /**
     * @inheritDoc
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * Set stage
     *
     * @param string $stage
     */
    public function setStage(string $stage): void
    {
        if (!in_array($stage, $this->getAllStages())) {
            throw new \InvalidArgumentException('No such stage');
        }

        $this->stage = $stage;
    }

    /**
     * @inheritDoc
     */
    public function getAllStages(): array
    {
        return $this->all;
    }

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        return [
            'stage' => $this->getStage(),
            'all'   => $this->getAllStages(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getStage();
    }
}
