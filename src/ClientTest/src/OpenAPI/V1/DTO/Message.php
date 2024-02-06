<?php
declare(strict_types=1);

namespace ClientTest\OpenAPI\V1\DTO;

use Articus\DataTransfer\Annotation as DTA;

/**
 */
class Message
{
    /**
     * @DTA\Data(field="level", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @DTA\Validator(name="Enum", options={"allowed":{
     *      "'emergency'",
     *      "'alert'",
     *      "'critical'",
     *      "'error'",
     *      "'warning'",
     *      "'notice'",
     *      "'info'",
     *      "INVALID_RESPONSE",
     *      "REQUEST_TIMEOUT",
     *      "SERVICE_UNAVAILABLE"
     * }})
     * @var string
     */
    public $level;
    /**
     * You can expose this enum for all your errors UNDEFINED - Any undefined message type LOGGER_MESSAGE - Same as undefined
     * @DTA\Data(field="type", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @DTA\Validator(name="Enum", options={"allowed":{
     *      "'UNDEFINED'",
     *      "'LOGGER_MESSAGE'",
     *      "'INVALID_RESPONSE'",
     *      "INVALID_RESPONSE",
     *      "REQUEST_TIMEOUT",
     *      "SERVICE_UNAVAILABLE"
     * }})
     * @var string
     */
    public $type;
    /**
     * Message, that describes what went wrong
     * @DTA\Data(field="text", nullable=true)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    public $text;
}
