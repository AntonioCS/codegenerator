<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;


use Inflyter\CodeGenerator\Traits\HasAnnotationTrait;
use Inflyter\CodeGenerator\Type\AbstractParameter;

/**
 * @method CGMethod end()
 */
class CGMethodParameter extends AbstractParameter
{
    use HasAnnotationTrait;

//    public function end() : CGMethod
//    {
//        if ($this->getParent()) {
//            /** @var CGMethod $parent */
//            $parent = $this->getParent();
//            return $parent;
//        }
//
//        throw new \RuntimeException('No parent set');
//    }
}