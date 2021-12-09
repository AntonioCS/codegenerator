<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


use Inflyter\CodeGenerator\Type\CGAnnotation;

trait HasNotPermittedToHaveAnnotations
{
    public function getAnnotation() : CGAnnotation
    {
        throw new \RuntimeException('This type is not permitted to have annotations');
    }

    public function hasAnnotation() : bool
    {
        return false;
    }
}