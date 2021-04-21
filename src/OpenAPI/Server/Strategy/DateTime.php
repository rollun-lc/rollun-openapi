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
     * @inheritdoc
     */
    public function extract($from)
    {
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
     * @param $arrayValue
     *
     * @return bool|\DateTime
     */
    protected function parseDateString(string $value): \DateTime
    {
        return \DateTime::createFromFormat(static::DATE_TIME_FORMAT, $value, new \DateTimeZone('UTC'));
    }
}
