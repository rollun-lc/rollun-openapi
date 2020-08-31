<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample\Result\Data;

/**
 * Class FileSummaryResult
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class FileSummaryResult
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
}
