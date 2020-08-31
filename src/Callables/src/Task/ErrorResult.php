<?php
declare(strict_types=1);

namespace rollun\Callables\Task;

use Psr\Log\LogLevel;
use rollun\Callables\Task\Result\MessageInterface;

/**
 * Class ErrorResult
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class ErrorResult implements ErrorResultInterface
{
    /**
     * @var MessageInterface[]
     */
    protected $messages;

    /**
     * ErrorResult constructor.
     *
     * @param array $messages
     */
    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
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
            if ($message->getLevel() == LogLevel::ERROR) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function toArrayForDto(): array
    {
        $messages = [];
        foreach ($this->getMessages() as $message) {
            $messages[] = $message->toArrayForDto();
        }

        return [
            'messages' => $messages,
        ];
    }
}
