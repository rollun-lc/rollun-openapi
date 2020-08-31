<?php
declare(strict_types=1);

namespace rollun\Callables\Task\Result;

/**
 * Class Message
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Message implements MessageInterface
{
    /**
     * @var string
     */
    protected $level;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $context;

    /**
     * Message constructor.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    public function __construct(string $level, string $message, array $context = [])
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function getContext(): ?array
    {
        return $this->context;
    }
}
