<?php

namespace OpenAPI\Server\Validator;

use Zend\Validator\Date;

class DateTime extends Date
{
    public const RFC3339 = 'RFC3339';

    /**
     * RFC 3339, section 5.6 DateTime format
     * for example, 2017-07-21T17:32:28Z or with optional fractional seconds, 2017-07-21T17:32:28.123Z.
     *
     * @var array<string>
     */
    private static $RFC339Formats = ['Y-m-d\TH:i:sP', 'Y-m-d H:i:sP', 'Y-m-d\TH:i:s.uP', 'Y-m-d H:i:s.uP'];

    /**
     * @param $value
     * @return \DateTime|false
     */
    public function createFromRFC3339($value)
    {
        foreach (self::$RFC339Formats as $dateTimeFormat) {
            $dateTime = \DateTime::createFromFormat($dateTimeFormat, $value);
            if ($dateTime !== false) {
                return $dateTime;
            }
        }

        return false;
    }

    public function isStrict(): bool
    {
        // force disable strict comparing for RFC3339
        // because this comparing works with one format (see isValid method)
        if ($this->isRFC3339()) {
            return false;
        }

        return parent::isStrict();
    }

    public function isRFC3339(): bool
    {
        return $this->format === self::RFC3339;
    }

    /**
     * Attempts to convert a string into a DateTime object
     *
     * @param  string $value
     * @param  bool   $addErrors
     * @return bool|\DateTime
     */
    protected function convertString($value, $addErrors = true)
    {
        $date = $this->isRFC3339() ?
            $this->createFromRFC3339($value) : \DateTime::createFromFormat($this->format, $value);

        // Invalid dates can show up as warnings (ie. "2007-02-99")
        // and still return a DateTime object.
        $errors = \DateTime::getLastErrors();
        if ($errors['warning_count'] > 0) {
            if ($addErrors) {
                $this->error(self::FALSEFORMAT);
            }
            return false;
        }

        return $date;
    }
}