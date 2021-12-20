<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\HasAnnotationTrait;

/**
 * @method CGFunction end()
 */
class CGParameter extends AbstractParameter
{
    use HasAnnotationTrait;

//    public function end() : ?CGFunction
//    {
//        return $this->getParent();
//    }
}