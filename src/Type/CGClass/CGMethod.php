<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;


use Inflyter\CodeGenerator\Traits\HasEndClassReturn;
use Inflyter\CodeGenerator\Traits\HasStaticAccess;
use Inflyter\CodeGenerator\Traits\HasVisibilityTrait;
use Inflyter\CodeGenerator\Type\AbstractFunction;
use Inflyter\CodeGenerator\Type\CGClass;
use Inflyter\CodeGenerator\Type\CGVisibilityInterface;

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

//    public function setHasReturnType(bool $hasReturnType): static
//    {
//        return parent::setHasReturnType($hasReturnType);
//    }

    public function addParameter(string $name, ?string $type = null, ?string $defaultValue = null, bool $isNull = false) : CGMethodParameter
    {
        return $this->addParameterInternal(
            new CGMethodParameter($this, $name),
            $type, $defaultValue, $isNull
        );
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
