<?php
declare(strict_types=1);

namespace OpenAPI\Server\Validator;

use Laminas\Validator\AbstractValidator;
use Laminas\Validator\Exception;

/**
 * Class Type
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Type extends AbstractValidator
{
    const INVALID = 'typeInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates
        = [
            self::INVALID => 'Invalid type given.',
        ];

    /**
     * @var null|string
     */
    protected $type;

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isValid($value)
    {
        $result = true;
        if (!$this->checkType($value)) {
            $this->error(self::INVALID);
            $result = false;
        }
        return $result;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected function checkType($value): bool
    {
        switch ($this->type) {
            case 'int':
                return \is_int($value);
            case 'bool':
                return \is_bool($value);
            case 'float':
                return \is_float($value) || \is_int($value);
            case 'string':
                return \is_string($value);
            default:
                throw new \InvalidArgumentException(\sprintf('Can not check for type %s.', $this->type));
        }
    }
}
