<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\UsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\UsesNamespaceFromParentTrait;

abstract class AbstractFunction extends AbstractCGType
{
    use UsesUseStatementsFromParentTrait;
    use UsesNamespaceFromParentTrait;

    private bool $isAnonymous = false;

    private bool $isStatic = false;

    private bool $hasReturnType = true;

    private string $returnType = 'void';

    /**
     * @var CGParameter[]
     */
    private array $params = [];

    public function generateCode(): string
    {
        $codeBackUp = $this->code;
        $this->clearCode();

        $functionHeader = '';
        if ($this->isStatic()) {
            $functionHeader .= 'static ';
        }

        $functionHeader .= 'function';
        if ($this->isAnonymous() === false) {
            $functionHeader .= ' ' . $this->getName();
        }

        $functionHeader .= '(';

        if (!empty($this->params)) {
            $functionHeader .= implode (', ', $this->params);
        }

        $functionHeader .= ')';

        if ($this->hasReturnType()) {
            $functionHeader .= ': ' . $this->getReturnType();
        }

        $this->processAnnotation();

        $this
            ->addCodeLine($functionHeader)
            ->addScopeOpen()
            ->addCodeBlock(implode("\n", $codeBackUp))
            ->addScopeClose()
            ->addBlank()
        ;

        $final_code = $this->code;
        $this->code = $codeBackUp;

        return implode("\n", $final_code);
    }

    public function setName(string $name): static
    {
        parent::setName($name);
        //setName will do other transformations so we need to ensure lcfirst is called after
        $this->name = lcfirst($this->name);
        return $this;
    }

    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @param bool $isStatic
     * @return static
     */
    public function setIsStatic(bool $isStatic): self
    {
        $this->isStatic = $isStatic;
        return $this;
    }

    public function hasReturnType(): bool
    {
        return $this->hasReturnType;
    }

    public function setHasReturnType(bool $hasReturnType): AbstractCGType
    {
        $this->hasReturnType = $hasReturnType;
        return $this;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): self
    {
        $this->setHasReturnType(true);
        $this->returnType = $returnType;
        return $this;
    }

    public function setReturnTypeInt(): self
    {
        return $this->setReturnType('int');
    }

    public function setReturnTypeFloat(): self
    {
        return $this->setReturnType('float');
    }

    public function setReturnTypeBool(): self
    {
        return $this->setReturnType('bool');
    }

    public function setReturnTypeString(): self
    {
        return $this->setReturnType('string');
    }

    public function setReturnTypeArray(): self
    {
        return $this->setReturnType('array');
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(bool $isAnonymous): self
    {
        $this->isAnonymous = $isAnonymous;
        return $this;
    }

    protected function addParameterInternal(AbstractParameter $p, ?string $type = null, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter
    {
        if ($type) {
            $p->setTypes($type);
        }
        if ($defaultValue) {
            $p->setDefaultValue($defaultValue);
        }

        $p->setIsNull($isNull);

        $this->params[] = $p;

        return $p;
    }

    abstract public function addParameter(string $name, ?string $type = null, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter;

    public function addParameterTypeBool(string $name, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter
    {
        return $this->addParameter($name, 'bool', $defaultValue ?? 'false', $isNull);
    }

    public function addParameterTypeInt(string $name, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter
    {
        return $this->addParameter($name, 'int', $defaultValue ?? '0', $isNull);
    }

    public function addParameterTypeFloat(string $name, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter
    {
        return $this->addParameter($name, 'float', $defaultValue ?? '0',$isNull);
    }

    public function addParameterTypeString(string $name, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter
    {
        return $this->addParameter($name, 'string', $defaultValue ?? "''",$isNull);
    }

    public function addParameterTypeArray(string $name, ?string $defaultValue = null, bool $isNull = false) : AbstractParameter
    {
        return $this->addParameter($name, 'array', $defaultValue ?? '[]',$isNull);
    }

    /**
     * @param string $line
     * @return static
     */
    public function addReturn(string $line) : self
    {
        return $this->addCodeLine("return $line;");
    }
}
