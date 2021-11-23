<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


class CGParameter extends AbstractParameter
{
    public function end() : CGFunction
    {
        return $this->getParent();
    }

    public function addTextToAnnotation(string $text): CGParameter
    {
        return parent::addTextToAnnotation($text);
    }

}