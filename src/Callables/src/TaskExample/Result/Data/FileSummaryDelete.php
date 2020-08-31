<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample\Result\Data;

/**
 * Class FileSummaryDelete
 *
 * @author r.ratsun <r.ratsun@gmail.com>
 */
class FileSummaryDelete
{
    /**
     * @var bool
     */
    protected $isDeleted;

    /**
     * FileSummaryDelete constructor.
     *
     * @param bool $isDeleted
     */
    public function __construct(bool $isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return bool
     */
    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }
}
