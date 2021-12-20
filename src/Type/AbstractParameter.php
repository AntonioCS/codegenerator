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

    private mixed $defaultValue = null;

    private bool $doNotAddQuotesOnDefaultValueIfString = false;

    private bool $useDoubleQuotesForString = false;

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
            $surround = null;

            if ($this->doNotAddQuotesOnDefaultValueIfString() === false && is_string($this->getDefaultValue())) {
                $surround = ($this->isUseDoubleQuotesForString() ? '"' : "'");
            }

            if (is_bool($this->getDefaultValue()) || ($this->hasType() && $this->getTypes() === 'bool')) {
                $this->setDefaultValue($this->getDefaultValue() ? 'true' : 'false');
            }

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

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function hasDefaultValue() : bool
    {
        return ($this->defaultValue !== null);
    }

    public function doNotAddQuotesOnDefaultValueIfString(): bool
    {
        return $this->doNotAddQuotesOnDefaultValueIfString;
    }

    public function setDoNotAddQuotesOnDefaultValueIfString(bool $doNotAddQuotesOnDefaultValueIfString): self
    {
        $this->doNotAddQuotesOnDefaultValueIfString = $doNotAddQuotesOnDefaultValueIfString;
        return $this;
    }

    public function isUseDoubleQuotesForString(): bool
    {
        return $this->useDoubleQuotesForString;
    }

    public function setUseDoubleQuotesForString(bool $useDoubleQuotesForString): void
    {
        $this->useDoubleQuotesForString = $useDoubleQuotesForString;
    }
}
