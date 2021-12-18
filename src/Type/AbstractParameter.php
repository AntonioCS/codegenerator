<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\HasTypeTrait;
use Inflyter\CodeGenerator\Traits\HasUsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\HasUsesNamespaceFromParentTrait;
use Inflyter\CodeGenerator\Type\CGClass\CGMethod;


abstract class AbstractParameter extends AbstractCGType
{
    use HasUsesUseStatementsFromParentTrait;
    use HasUsesNamespaceFromParentTrait;
    use HasTypeTrait;

    private ?string $defaultValue = null;

    private bool $hasQuotesOnDefaultValue = false;

    protected bool $ignoreType = false;
    protected bool $noDollarSign = false;

    public function generateCode(): string
    {
        $code = '';
        $hasTypeAndIsNull = false;

        if (!$this->ignoreType && $this->hasType()) {
            if ($this->isNull()) {
                $hasTypeAndIsNull = true;
                $code .= '?';
            }
            $code .= $this->getTypes() . ' ';
        }

        $code .= (!$this->noDollarSign ? '$' : null) . $this->getName();

        if ($this->hasDefaultValue()) {
            $code .= ' = ';
            $surround = ($this->hasQuotesOnDefaultValue() || ($this->hasType() && $this->getTypes() === 'string')) ? "'" : null;
            $code .= $surround . $this->getDefaultValue() . $surround;
        } elseif ($hasTypeAndIsNull) {
            $code .= ' = null';
        }

        return $code;
    }

    public function setName(string $name): static
    {
        parent::setName($name);
        $this->name = lcfirst($this->name);
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function hasDefaultValue() : bool
    {
        return ($this->defaultValue !== null && $this->defaultValue !== '');
    }

    public function hasQuotesOnDefaultValue(): bool
    {
        return $this->hasQuotesOnDefaultValue;
    }

    public function setHasQuotesOnDefaultValue(bool $hasQuotesOnDefaultValue): self
    {
        $this->hasQuotesOnDefaultValue = $hasQuotesOnDefaultValue;
        return $this;
    }

}
