<?php
declare(strict_types=1);

namespace rollun\Callables\Task;

use rollun\Callables\Task\Async\Result\Data\TaskInfoInterface;
use rollun\Callables\Task\Result\MessageInterface;

/**
 * Class Result
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Result implements ResultInterface
{
    /**
     * @var TaskInfoInterface
     */
    protected $data;

    /**
     * @var MessageInterface[]
     */
    protected $messages;

    /**
     * Result constructor.
     *
     * @param null|object $data
     * @param array       $messages
     */
    public function __construct($data, array $messages = [])
    {
        $this->data = $data;
        $this->messages = $messages;
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
    public function getMessages(): ?array
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    public function addMessage(MessageInterface $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        foreach ($this->getMessages() as $message) {
            if ($message->getLevel() == 'Error') {
                return false;
            }
        }

        return true;
    }
}
