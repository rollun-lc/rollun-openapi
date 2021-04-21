<?php
declare(strict_types=1);

namespace OpenAPI\Server\Strategy;

/**
 * Class Date
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Date extends DateTime
{
    const DATE_TIME_FORMAT = 'Y-m-d';

    /**
     * @inheritdoc
     */
    protected function parseDateString(string $value): \DateTime
    {
        return \DateTime::createFromFormat(
            static::DATE_TIME_FORMAT . ' H:i:sP',
            $value . ' 00:00:00+00:00',
            new \DateTimeZone('UTC')
        );
    }
}
