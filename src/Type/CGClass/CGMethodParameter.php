<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;


use Inflyter\CodeGenerator\Traits\AnnotationTrait;
use Inflyter\CodeGenerator\Type\AbstractParameter;

class CGMethodParameter extends AbstractParameter
{
    use AnnotationTrait;

    public function end() : CGMethod
    {
        return parent::end();
    }
}