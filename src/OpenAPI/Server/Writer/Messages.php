<?php
declare(strict_types=1);

namespace OpenAPI\Server\Writer;

use Zend\Log\Writer\AbstractWriter;

/**
 * Class Messages
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Messages extends AbstractWriter
{
    const KEY_LEVEL = 'level';
    const KEY_MESSAGE = 'message';

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @inheritDoc
     */
    protected function doWrite(array $event)
    {
        $this->messages[] = [
            self::KEY_LEVEL   => $event[self::KEY_LEVEL],
            self::KEY_MESSAGE => $event[self::KEY_MESSAGE],
        ];
    }
}
