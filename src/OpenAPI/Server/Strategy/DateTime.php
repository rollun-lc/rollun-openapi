<?php
declare(strict_types=1);

namespace OpenAPI\Server\Strategy;

use Articus\DataTransfer\Strategy\StrategyInterface;

/**
 * Class DateTime
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class DateTime implements StrategyInterface
{
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * RFC 3339, section 5.6 DateTime format
     * for example, 2017-07-21T17:32:28Z or with optional fractional seconds, 2017-07-21T17:32:28.123Z.
     *
     * @var array<string>
     */
    private static $RFC339Formats = ['Y-m-d\TH:i:sP', 'Y-m-d H:i:sP', 'Y-m-d\TH:i:s.uP', 'Y-m-d H:i:s.uP'];

    /**
     * @inheritdoc
     */
    public function extract($from)
    {
        if (is_null($from)) {
            return null;
        }

        if ($from instanceof \DateTime) {
            return $from->format(static::DATE_TIME_FORMAT);
        }

        throw new \LogicException(\sprintf(
            'Extraction can be done only from %s, not %s',
            \DateTime::class, \is_object($from) ? \get_class($from) : \gettype($from)
        ));
    }

    /**
     * @inheritdoc
     */
    public function hydrate($from, &$to): void
    {
        $to = null;
        if (!empty($from)) {
            $date = $this->parseDateString($from);
            if ($date instanceof \DateTime) {
                $to = $date;
            }
        }
    }

    /**
     * @param string $value
     * @return bool|\DateTime
     */
    protected function parseDateString(string $value)
    {
        foreach (self::$RFC339Formats as $dateTimeFormat) {
            $dateTime = \DateTime::createFromFormat($dateTimeFormat, $value, new \DateTimeZone('UTC'));
            if ($dateTime !== false) {
                return $dateTime;
            }
        }

        return false;
    }

    public function merge($from, &$to): void
    {
        $to = $from;
    }
}
