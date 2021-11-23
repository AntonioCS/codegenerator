<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait AnnotationTrait
{
    /**
     * @param string $text
     * @return static
     */
    public function addTextToAnnotation(string $text)
    {
        parent::addTextToAnnotation($text);
        return $this;
    }
}