<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;


use Inflyter\CodeGenerator\Traits\HasEndClassReturn;
use Inflyter\CodeGenerator\Traits\HasStaticAccess;
use Inflyter\CodeGenerator\Traits\HasVisibilityTrait;
use Inflyter\CodeGenerator\Type\AbstractFunction;
use Inflyter\CodeGenerator\Type\CGVisibilityInterface;

/**
 * @method CGMethodParameter addParameterTypeBool(string $name, ?bool $defaultValue = null, bool $isNull = false)
 * @method CGMethodParameter addParameterTypeInt(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGMethodParameter addParameterTypeFloat(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGMethodParameter addParameterTypeString(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGMethodParameter addParameterTypeArray(string $name, ?string $defaultValue = null, bool $isNull = false)
 */
class CGMethod extends AbstractFunction implements CGVisibilityInterface
{
    use HasVisibilityTrait;
    use HasEndClassReturn;
    use HasStaticAccess;

    public function generateCode(): string
    {
        if ($this->isAnonymous()) {
            throw new \RuntimeException('A method can\'t be an anonymous function');
        }

        return $this->getVisibility() . ' '. parent::generateCode();
    }

    public function addParameter(string $name, ?string $type = null, mixed $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        $p = new CGMethodParameter($this, $name);
        $this->addParameterInternal($p, $type, $defaultValue, $isNull);
        return $p;
    }
}
