<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;

use Inflyter\CodeGenerator\Type\CGTypeInterface;

trait HasUsesUseStatementsFromParentTrait
{
    /**
     * @return static
     */
    public function clearUseStatements(): self
    {
        $parent = $this->getParent();
        if ($parent !== null) {
            $parent->clearUseStatements();
        }

        return $this;
    }

    /**
     * @param string $use
     * @return static
     */
    public function addUseStatement(string $use): self
    {
        $parent = $this->getParent();
        if ($parent !== null) {
            $parent->addUseStatement($use);
        }

        return $this;
    }

    public function hasUseStatements(): bool
    {
        $parent = $this->getParent();
        if ($parent !== null) {
            return $parent->hasUseStatements();
        }

        return false;
    }

    public function getUseStatements(): array
    {
        $parent = $this->getParent();
        if ($parent !== null) {
            return $parent->getUseStatements();
        }

        throw new \RuntimeException('This class does not have a parent');
    }
}