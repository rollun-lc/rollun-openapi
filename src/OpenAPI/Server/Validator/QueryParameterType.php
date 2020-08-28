<?php
declare(strict_types=1);

namespace OpenAPI\Server\Validator;

use OpenAPI\Server\Strategy\QueryParameter;

/**
 * Class QueryParameterType
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class QueryParameterType extends Type
{
    const RE_INT = '0|-?[1-9]\d*';
    const RE_BOOL = 'true|false';
    const RE_FLOAT = '0(\.\d+)?|-?[1-9]\d*(\.\d+)?|-0\.\d+';

    /**
     * @param $value
     *
     * @return bool
     */
    protected function checkType($value): bool
    {
        switch ($this->type) {
            case QueryParameter::TYPE_INT:
                return \is_string($value) && \preg_match('/^(' . self::RE_INT . ')$/', $value);
            case QueryParameter::TYPE_BOOL:
                return \is_string($value) && \preg_match('/^(' . self::RE_BOOL . ')$/', $value);
            case QueryParameter::TYPE_FLOAT:
                return \is_string($value) && \preg_match('/^(' . self::RE_FLOAT . ')$/', $value);
            case QueryParameter::TYPE_STRING:
                return \is_string($value);
            default:
                throw new \InvalidArgumentException(\sprintf('Can not check for type %s.', $this->type));
        }
    }
}
