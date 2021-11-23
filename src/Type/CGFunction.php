<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;

use Inflyter\CodeGenerator\Traits\AnnotationTrait;

class CGFunction extends AbstractFunction
{
    public function addTextToAnnotation(string $text) : CGFunction
    {
        parent::addTextToAnnotation($text);
        return $this;
    }

    public function end(): CGFile
    {
        return $this->getParent();
    }

    public function addParameter(string $name, ?string $type = null, ?string $defaultValue = null, bool $isNull = false) : CGParameter
    {
        $p = new CGParameter($this, $name);
        return $this->addParameterInternal($p, $type, $defaultValue, $isNull);
    }

    public function addParameterTypeBool(string $name, ?string $defaultValue = null, bool $isNull = false) : CGParameter
    {
        return parent::addParameterTypeBool($name, $defaultValue, $isNull);
    }

    public function addParameterTypeInt(string $name, ?string $defaultValue = null, bool $isNull = false) : CGParameter
    {
        return parent::addParameterTypeInt($name, $defaultValue, $isNull);
    }

    public function addParameterTypeFloat(string $name, ?string $defaultValue = null, bool $isNull = false) : CGParameter
    {
        return parent::addParameterTypeFloat($name, $defaultValue, $isNull);
    }

    public function addParameterTypeString(string $name, ?string $defaultValue = null, bool $isNull = false) : CGParameter
    {
        return parent::addParameterTypeString($name, $defaultValue, $isNull);
    }

    public function addParameterTypeArray(string $name, ?string $defaultValue = null, bool $isNull = false) : CGParameter
    {
        return parent::addParameterTypeArray($name, $defaultValue, $isNull);
    }
}
