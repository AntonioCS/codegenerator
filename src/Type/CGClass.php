<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;

use Inflyter\CodeGenerator\Type\CGClass\CGMethod;
use Inflyter\CodeGenerator\Type\CGClass\CGProperty;
use Inflyter\CodeGenerator\Traits\UsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\UsesNamespaceFromParentTrait;

class CGClass extends AbstractCGType
{
    use UsesUseStatementsFromParentTrait;
    use UsesNamespaceFromParentTrait;

    /**
     * @var CGProperty[]
     */
    private array $properties = [];

    /**
     * @var CGMethod[]
     */
    private array $methods = [];

    private ?string $extends = null;

    /**
     * @var string[]
     */
    private array $interfaces = [];

    /**
     * @var string[]
     */
    private array $classUses = [];

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

    public function addProperty(string $name, ?string $type = null, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        $p = new CGProperty($this, $name);

        $p->setType($type);
        $p->setDefaultValue($defaultValue);
        $p->setIsNull($isNull);

        $this->properties[] = $p;

        return  $p;
    }

    public function addProprietyTypeBool(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'bool', $defaultValue ?? 'false', $isNull);
    }

    public function addProprietyTypeInt(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'int', $defaultValue ?? '0', $isNull);
    }

    public function addProprietyTypeFloat(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'float', $defaultValue ?? '0',$isNull);
    }

    public function addProprietyTypeString(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'string', $defaultValue ?? "''",$isNull);
    }

    public function addProprietyTypeArray(string $name, ?string $defaultValue = null, bool $isNull = false) : CGProperty
    {
        return $this->addProperty($name, 'array', $defaultValue ?? '[]',$isNull);
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
                if ($method->getName() === $name)
                    return $method;
            }
        }

        throw new \Exception("Method name $name not found in class {$this->getName()}");
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

        $this
            ->addCodeLine($classHeader)
            ->addScopeOpen()
            ->addBlank()
        ;

        if ($this->hasClassUses()) {
            $uses = array_unique($this->classUses);

            foreach ($uses as $use) {
                $this->addCodeLine("use $use;");
            }

            $this->addBlank();
        }

        if ($this->hasProperties()) {
            $this->sortByVisibility($this->properties);

            foreach ($this->properties as $property) {
                $this
                    ->addCodeBlock($property->generateCode())
                    ->addBlank()
                ;
            }
        }

        if ($this->hasMethods()) {
            $this->sortByVisibility($this->methods);

            foreach ($this->methods as $method) {
                $this
                    ->addCodeBlock($method->generateCode()) //GCFunction adds a blank
                ;
            }
        }

        $this
            ->addScopeClose()
            ->addBlank()
        ;

        return implode("\n", $this->code);
    }

    public function addClassUse(string $use) : self
    {
        $this->classUses[] = $use;
        return $this;
    }

    public function getClassUses() : array
    {
        return $this->classUses;
    }

    public function hasClassUses() : bool
    {
        return !empty($this->classUses);
    }

    public function addTextToAnnotation(string $text) : CGClass
    {
        parent::addTextToAnnotation($text);
        return $this;
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

                if ($a->getVisibility() === 'public')
                    return 1;

                if ($b->getVisibility() === 'public')
                    return -1;

                return 0;
            }
        );
    }

    public function end() : CGFile
    {
        return parent::end();
    }
}
