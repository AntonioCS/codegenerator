<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


/**
 * @method CGParameter addParameterTypeBool(string $name, ?bool $defaultValue = null, bool $isNull = false)
 * @method CGParameter addParameterTypeInt(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGParameter addParameterTypeFloat(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGParameter addParameterTypeString(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGParameter addParameterTypeArray(string $name, ?string $defaultValue = null, bool $isNull = false)
 * @method CGFile end()
 */
class CGFunction extends AbstractFunction
{
    public function addTextToAnnotation(string $text) : static
    {
        parent::addTextToAnnotation($text);
        return $this;
    }

    public function addParameter(string $name, ?string $type = null, mixed $defaultValue = null, bool $isNull = false) : CGParameter
    {
        $p = new CGParameter($this, $name);
        $this->addParameterInternal($p, $type, $defaultValue, $isNull);
        return $p;
    }
}
