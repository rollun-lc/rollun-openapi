<?php
declare(strict_types=1);

namespace OpenAPI\Server\Strategy;

use function implode;
use function is_array;

/**
 * Class QueryParameterArray
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class QueryParameterArray extends QueryParameter
{
    const FORMAT_CSV = 'csv'; //comma separated values foo,bar.
    const FORMAT_SSV = 'ssv'; //space separated values foo bar.
    const FORMAT_TSV = 'tsv'; //tab separated values foo\tbar.
    const FORMAT_PIPES = 'pipes'; //pipe separated values foo|bar.
    const FORMAT_MULTI = 'multi'; //corresponds to multiple parameter instances instead of multiple values for a single instance foo[]=bar&foo[]=baz.

    const DELIMITER_MAP
        = [
            self::FORMAT_CSV   => ',',
            self::FORMAT_SSV   => ' ',
            self::FORMAT_TSV   => "\t",
            self::FORMAT_PIPES => '|',
            self::FORMAT_MULTI => null,
        ];

    /**
     * @var string|null
     */
    protected $delimiter;

    public function __construct(array $options)
    {
        parent::__construct($options);
        if (empty($options['format'])) {
            throw new \InvalidArgumentException('Option "format" is required.');
        } elseif (!\array_key_exists($options['format'], self::DELIMITER_MAP)) {
            throw new \InvalidArgumentException(\sprintf('Unknown format "%s".', $options['format']));
        }
        $this->delimiter = self::DELIMITER_MAP[$options['format']];
    }

    /**
     * @inheritdoc
     */
    public function extract($from)
    {
        $result = null;
        if (is_array($from)) {
            if ($this->delimiter === null) {
                $result = $from;
            } else {
                $result = implode($this->delimiter, $from);
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function hydrate($from, &$to): void
    {
        $to = null;
        if ($from !== null) {
            if ($this->delimiter === null) {
                $list = (is_array($from)) ? $from : [$from];
            } else {
                $list = \explode($this->delimiter, $from);
            }
            $to = [];
            foreach ($list as $key => $item) {
                $to[$key] = $this->parentHydrate($item);
            }
        }
    }

    private function parentHydrate($from)
    {
        $value = null;
        parent::hydrate($from, $value);
        return $value;
    }
}
