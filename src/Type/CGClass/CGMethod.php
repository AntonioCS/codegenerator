<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;


use Inflyter\CodeGenerator\Traits\HasEndClassReturn;
use Inflyter\CodeGenerator\Traits\HasVisibilityTrait;
use Inflyter\CodeGenerator\Type\AbstractFunction;
use Inflyter\CodeGenerator\Type\CGClass;
use Inflyter\CodeGenerator\Type\CGVisibilityInterface;

class CGMethod extends AbstractFunction implements CGVisibilityInterface
{
    use HasVisibilityTrait;
    use HasEndClassReturn;

    public function generateCode(): string
    {
        return $this->getVisibility() . ' ' . parent::generateCode();
    }

    public function setHasReturnType(bool $hasReturnType): CGMethod
    {
        return parent::setHasReturnType($hasReturnType);
    }

    public function addParameter(string $name, ?string $type = null, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        $p = new CGMethodParameter($this, $name);
        return $this->addParameterInternal($p, $type, $defaultValue, $isNull);
    }

    public function addParameterTypeBool(string $name, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        return parent::addParameterTypeBool($name, $defaultValue, $isNull);
    }

    public function addParameterTypeInt(string $name, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        return parent::addParameterTypeInt($name, $defaultValue, $isNull);
    }

    public function addParameterTypeFloat(string $name, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        return parent::addParameterTypeFloat($name, $defaultValue, $isNull);
    }

    public function addParameterTypeString(string $name, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        return parent::addParameterTypeString($name, $defaultValue, $isNull);
    }

    public function addParameterTypeArray(string $name, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        return parent::addParameterTypeArray($name, $defaultValue, $isNull);
    }
}
