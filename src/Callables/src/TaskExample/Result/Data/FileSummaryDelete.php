<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample\Result\Data;

use rollun\Callables\Task\ToArrayForDtoInterface;

/**
 * Class FileSummaryDelete
 *
 * @author r.ratsun <r.ratsun@gmail.com>
 */
class FileSummaryDelete implements ToArrayForDtoInterface
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

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        return [
            'isDeleted' => $this->getIsDeleted()
        ];
    }
}
