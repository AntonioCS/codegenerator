<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;

use Inflyter\CodeGenerator\Type\AbstractCGType;

trait HasNoNamePassedInConstructor
{
    public function __construct(AbstractCGType $parent, int $indentation = 0)
    {
        parent::__construct($parent, '', $indentation);
        $this->mustHaveParent();
    }
}