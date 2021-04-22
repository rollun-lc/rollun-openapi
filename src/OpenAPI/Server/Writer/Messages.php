<?php
declare(strict_types=1);

namespace OpenAPI\Server\Writer;

use rollun\logger\Writer\AbstractWriter;

/**
 * Class Messages
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Messages extends AbstractWriter
{
    const KEY_LEVEL = 'level';
    const KEY_MESSAGE = 'message';
    const KEY_TYPE = 'type';

    const OPENAPI_KEY = 'openapi';

    const TYPE_LOGGER_MESSAGE = "LOGGER_MESSAGE";

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
            self::KEY_TYPE => $event['context'][self::OPENAPI_KEY][self::KEY_TYPE] ?? self::TYPE_LOGGER_MESSAGE,
            self::KEY_MESSAGE => $event[self::KEY_MESSAGE],
        ];
    }
}
