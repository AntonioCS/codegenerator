<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


use Inflyter\CodeGenerator\Type\CGClass;

trait HasEndClassReturn
{
    public function end() : CGClass
    {
        return $this->getParent();
    }
}