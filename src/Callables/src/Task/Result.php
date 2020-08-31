<?php
declare(strict_types=1);

namespace rollun\Callables\Task;

use rollun\Callables\Task\Async\Result\Data\TaskInfoInterface;

/**
 * Class Result
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Result extends ErrorResult implements ResultInterface
{
    /**
     * @var TaskInfoInterface
     */
    protected $data;

    /**
     * Result constructor.
     *
     * @param null|object $data
     * @param array       $messages
     */
    public function __construct($data, array $messages = [])
    {
        parent::__construct($messages);

        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        $result = parent::toArrayForDto();
        $result['data'] = !empty($data = $this->getData()) ? $data->toArrayForDto() : null;

        return $result;
    }
}
