<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\HasTypeTrait;
use Inflyter\CodeGenerator\Traits\UsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\UsesNamespaceFromParentTrait;


abstract class AbstractParameter extends AbstractCGType
{
    use UsesUseStatementsFromParentTrait;
    use UsesNamespaceFromParentTrait;
    use HasTypeTrait;

    private ?string $defaultValue = null;

    private bool $hasQuotesOnDefaultValue = false;

    public function generateCode(): string
    {
        $code = '';

        if ($this->hasType()) {
            if ($this->isNull()) $code .= '?';
            $code .= $this->getType() . ' ';
        }

        $code .= '$' . $this->getName();

        if ($this->hasDefaultValue()) {
            $code .= ' = ';
            $surround = ($this->hasQuotesOnDefaultValue() || ($this->hasType() && $this->getType() === 'string')) ? "'" : null;
            $code .= $surround . $this->getDefaultValue() . $surround;
        }

        return $code;
    }

    public function setName(string $name): self
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

//    public function getAnnotation() : CGAnnotation
//    {
//        throw new \Exception('This type is not permitted to have annotations');
//    }
//
//    public function hasAnnotation() : bool
//    {
//        return false;
//    }
}
