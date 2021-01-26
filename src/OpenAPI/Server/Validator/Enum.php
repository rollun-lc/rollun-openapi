<?php


namespace OpenAPI\Server\Validator;


use Zend\Validator\AbstractValidator;

class Enum extends AbstractValidator
{
    const INVALID = 'enumInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates
        = [
            self::INVALID => "Value should be one of: [%value%]",
        ];

    protected $allowed = [];

    public function setAllowed($allowed): void
    {
        $this->allowed = $allowed;
    }

    public function isValid($value): bool
    {
        if (!$result = in_array($value, $this->allowed))
        {
            $this->error(self::INVALID, implode(', ',$this->allowed));
        }

        return $result;
    }
}