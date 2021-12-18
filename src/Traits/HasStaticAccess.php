<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;

trait HasStaticAccess
{
    private bool $isStatic = false;

    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    public function setIsStatic(bool $isStatic): self
    {
        $this->isStatic = $isStatic;
        return $this;
    }

    protected function getStaticAccess() : ?string
    {
        return ($this->isStatic() ? 'static ' : null);
    }

}