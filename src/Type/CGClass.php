<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;

use Inflyter\CodeGenerator\Traits\HasAnnotationTrait;
use Inflyter\CodeGenerator\Type\CGClass\CGConst;
use Inflyter\CodeGenerator\Type\CGClass\CGMethod;
use Inflyter\CodeGenerator\Type\CGClass\CGProperty;
use Inflyter\CodeGenerator\Traits\HasUsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\HasUsesNamespaceFromParentTrait;

class CGClass extends AbstractCGType
{
    use HasUsesUseStatementsFromParentTrait;
    use HasUsesNamespaceFromParentTrait;
    use HasAnnotationTrait;

    /**
     * @var CGProperty[]
     */
    private array $properties = [];

    /**
     * @var CGMethod[]
     */
    private array $methods = [];

    /** @var CGConst[] */
    private array $consts = [];

    private ?string $extends = null;

    /**
     * @var string[]
     */
    private array $interfaces = [];

    /**
     * @var array<array{0:string, 1:?string}>
     */
    private array $traits = [];

    public function setExtends(string $className) : self
    {
        $this->extends = $className;
        return $this;
    }

    public function clearExtends() : self
    {
        $this->extends = null;
        return $this;
    }

    public function getExtends() : ?string
    {
        return $this->extends;
    }

    public function addInterface(string $interfaceName) : self
    {
        $this->interfaces[] = $interfaceName;
        return $this;
    }

    public function getInterfaces() : array
    {
        return $this->interfaces;
    }

    public function hasInterfaces() : bool
    {
        return !empty($this->interfaces);
    }

    public function clearInterfaces() : self
    {
        $this->interfaces = [];
        return $this;
    }

    public function addProperty(string $name, ?string $type = null, mixed $defaultValue = null, bool $isNull = false) : CGProperty
    {
        $p = new CGProperty($this, $name);

        $p->setTypes($type);
        $p->setDefaultValue($defaultValue);
        $p->setIsNull($isNull);

        $this->properties[] = $p;

        return  $p;
    }

    public function addConst(string $name, mixed $value) : CGConst
    {
        $c = new CGConst($this, $name);

        $c->setDefaultValue($value);

        $this->consts[] = $c;

        return $c;
    }

    public function hasConsts() : bool
    {
        return !empty($this->consts);
    }

    public function addPropertyTypeBool(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'bool', $defaultValue ?? 'false', $isNull);
    }

    public function addPropertyTypeInt(string $name, ?int $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'int', $defaultValue, $isNull);
    }

    public function addPropertyTypeFloat(string $name, ?float $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'float', $defaultValue,$isNull);
    }

    public function addPropertyTypeString(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'string', $defaultValue,$isNull);
    }

    public function addPropertyTypeArray(string $name, ?array $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'array', $defaultValue,$isNull);
    }

    public function hasProperties() : bool
    {
        return !empty($this->properties);
    }

    public function addMethod(string $name) : CGMethod
    {
        $m = new CGMethod($this, $name);
        $this->methods[] = $m;

        return $m;
    }

    public function hasMethods() : bool
    {
        return !empty($this->methods);
    }

    public function findMethod(string $name) : CGMethod
    {
        if ($this->hasMethods()) {
            foreach ($this->methods as $method) {
                if ($method->getName() === $name) {
                    return $method;
                }
            }
        }

        throw new \RuntimeException("Method name $name not found in class {$this->getName()}");
    }

    public function generateCode(): string
    {
        $classHeader = "class " . $this->getName();

        $extends = $this->getExtends();
        if (!empty($extends)) {
            $classHeader .= ' extends ' . $extends;
        }

        if ($this->hasInterfaces()) {
            $classHeader .= ' implements ' . implode(', ', $this->getInterfaces());
        }

        if ($this->hasAnnotation()) {
            $this->addCodeLine($this->getAnnotation()->generateCode());
        }

        if ($this->hasAttribute()) {
            $this->addCodeLine($this->getAttribute()->generateCode());
        }

        $this
            ->addCodeLine($classHeader)
            ->addScopeOpen()
            ->addBlank()
        ;

        if ($this->hasTraits()) {
            $uses = [];

            foreach ($this->traits as [$use, $as]) {
                if (isset($uses[$use])) {
                    continue;
                }

                $theUse = "use $use" . ($as !== null ? " as $as;" : ';');
                $this->addCodeLine($theUse);

                $uses[$use] = 1;
            }

            $this->addBlank();
        }

        if ($this->hasConsts()) {
            $this->generateClassInternalElements($this->consts);
        }

        if ($this->hasProperties()) {
            $this->generateClassInternalElements($this->properties);
        }

        if ($this->hasMethods()) {
            $this->generateClassInternalElements($this->methods, false);
        }

        $this
            ->addScopeClose()
            ->addBlank()
        ;

        return $this->getFormattedCode();
    }

    private function generateClassInternalElements(array $items, bool $addBlack = true) : void
    {
        $this->sortByVisibility($items);

        foreach ($items as $item) {
            $this
                ->addCodeBlock($item->generateCode())
                ->addBlankIf($addBlack)
            ;
        }
    }

    public function addTrait(string $fullTraitPath, ?string $as = null, bool $passToParentAddUseStatement = true) : self
    {
        if ($passToParentAddUseStatement) {
            $this->addUseStatement($fullTraitPath);
        }

        $this->traits[] = [$this->getClassName($fullTraitPath), $as];
        return $this;
    }

    private function getClassName(string $fullyQualifiedClassName) : string
    {
        if ($pos = strrpos($fullyQualifiedClassName, '\\')) {
            return substr($fullyQualifiedClassName, $pos + 1);
        }
        return $fullyQualifiedClassName;
    }

    public function getTraits() : array
    {
        return $this->traits;
    }

    public function hasTraits() : bool
    {
        return !empty($this->traits);
    }

    /**
     * @param CGVisibilityInterface[] $objs
     */
    private function sortByVisibility(array &$objs) : void
    {
        usort($objs,
            static function(CGVisibilityInterface $a, CGVisibilityInterface $b) : int {
                if ($a->getVisibility() === $b->getVisibility()) {
                    return 0;
                }

                if ($a->getVisibility() === 'public') {
                    return 1;
                }

                if ($b->getVisibility() === 'public') {
                    return -1;
                }

                return 0;
            }
        );
    }
}
