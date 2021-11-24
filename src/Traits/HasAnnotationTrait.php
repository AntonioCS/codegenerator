<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait HasAnnotationTrait
{

    public function addTextToAnnotation(string $text) : static
    {
        parent::addTextToAnnotation($text);
        return $this;
    }
}