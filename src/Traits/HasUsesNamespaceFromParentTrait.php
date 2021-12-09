<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait HasUsesNamespaceFromParentTrait
{

    public function setNamespace(string $namespace): static
    {
        $parent = $this->getParent();

        if ($parent !== null) {
            $parent->setNamespace($namespace);
        } else {
            throw new \RuntimeException('This class does not have a parent');
        }

        return $this;
    }

    public function getNameSpace(): ?string
    {
        $parent = $this->getParent();

        if ($parent !== null) {
            return $parent->getNameSpace();
        }

        return null;
    }

    public function hasNameSpace(): bool
    {
        $parent = $this->getParent();

        if ($parent !== null) {
            return $parent->hasNameSpace();
        }

        return false;
    }
}