<?php
declare(strict_types=1);

namespace rollun\Callables\TaskExample\Model;

/**
 * Class CreateTaskParameters
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class CreateTaskParameters
{
    /**
     * @var int
     */
    protected $n;

    /**
     * Parameters constructor.
     *
     * @param int $n
     */
    public function __construct(int $n)
    {
        $this->n = $n;
    }

    /**
     * @return int
     */
    public function getN()
    {
        return $this->n;
    }
}
