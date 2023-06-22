<?php


namespace OpenAPI\Server\Validator;


use Laminas\Validator\AbstractValidator;

class Enum extends AbstractValidator
{
    public const INVALID = 'enumInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates
        = [
            self::INVALID => "The value '%value%' not in enum list.",
        ];

    protected $allowed = [];

    public function setAllowed($allowed): void
    {
        $this->allowed = array_map(function ($item) {
            return $this->castToType($item);
        }, $allowed);
    }

    /**
     * Converts variable to their best type
     *
     * @param $item
     * @return bool|float|int|string
     */
    protected function castToType($item)
    {
        if (filter_var($item, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) !== null) {
            return (int)$item;
        }
        if (filter_var($item, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) !== null) {
            return (float)$item;
        }

        if (is_string($item)) {
            $first = substr($item, 0, 1);
            $last = substr($item, -1);

            // if string wrapped in single quotes
            if ($first === "'" && $last === "'") {
                // string without quotes
                $item = substr($item, 1, -1);
            }

            // validate bool
            // I'm not use filter_var cause FILTER_VALIDATE_BOOLEAN tries to be smart, recognizing words like Yes, No,
            // Off, On, both string and native types of true and false.
            $lowerItem = strtolower($item);
            if ($lowerItem === 'false') return false;
            elseif ($lowerItem === 'true') return true;

            return (string)$item;
        }

        return $item;
    }

    public function isValid($value): bool
    {
        if (is_array($value)) {
            return $this->validateArray($value);
        }

        return $this->isInArray($value);
    }

    protected function isInArray($value): bool
    {
        if (!$result = in_array($value, $this->allowed, true)) {
            $this->error(self::INVALID, $value);
        }
        return $result;
    }

    protected function validateArray($value): bool
    {
        foreach ($value as $item) {
            if (!$this->isInArray($item)) {
                return false;
            }
        }
        return true;
    }
}