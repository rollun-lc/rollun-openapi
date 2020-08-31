<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample\Result\Data;

use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 * Class FileSummaryResult
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class FileSummaryResult implements ToArrayForDtoInterface
{
    /**
     * @var int
     */
    protected $summary;

    /**
     * FileSummaryResult constructor.
     *
     * @param int $summary
     */
    public function __construct(int $summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return int
     */
    public function getSummary(): int
    {
        return $this->summary;
    }

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        return [
            'summary' => $this->getSummary()
        ];
    }
}
