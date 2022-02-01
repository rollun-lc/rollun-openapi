<?php
declare(strict_types=1);

namespace OpenAPI\Server\Strategy;

use Articus\DataTransfer\Strategy\StrategyInterface;

/**
 * Class QueryParameter
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class QueryParameter implements StrategyInterface
{
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOL = 'bool';
    const TYPE_STRING = 'string';

    const TYPE_MAP
        = [
            self::TYPE_INT    => true,
            self::TYPE_FLOAT  => true,
            self::TYPE_BOOL   => true,
            self::TYPE_STRING => true,
        ];

    /**
     * @var string
     */
    protected $type;

    /**
     * QueryParameterArray constructor.
     */
    public function __construct(array $options)
    {
        if (empty($options['type'])) {
            throw new \InvalidArgumentException('Option "type" is required.');
        } elseif (!isset(self::TYPE_MAP[$options['type']])) {
            throw new \InvalidArgumentException(\sprintf('Unknown type "%s".', $options['type']));
        }
        $this->type = $options['type'];
    }


    /**
     * @inheritdoc
     */
    public function extract($from)
    {
        $result = null;
        if ($from !== null) {
            $result = (string)$from;
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
            switch ($this->type) {
                case self::TYPE_INT:
                    $to = (int)$from;
                    break;
                case self::TYPE_FLOAT:
                    $to = (float)$from;
                    break;
                case self::TYPE_BOOL:
                    $to = $from === 'true';
                    break;
                case self::TYPE_STRING:
                    $to = (string)$from;
                    break;
            }
        }
    }

    // TODO Не разобрался до конца
    public function merge($from, &$to): void
    {
        //return $from;
        $to = $from;
    }
}
