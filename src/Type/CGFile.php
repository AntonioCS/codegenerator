<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


class CGFile extends AbstractCGType
{
    private bool $addGeneratedMarker = false;

    private bool $strictTypes = false;

    private ?string $path;

    /**
     * @var string[]
     */
    private array $useStatements = [];

    private ?string $namespace = null;

    /**
     * @var CGClass[]
     */
    private array $classes;

    /**
     * @var array<int, int>
     */
    private array $mapper_classes;

    /**
     * @var CGFunction[]
     */
    private array $functions;

    /**
     * @var array<int, int[]>
     */
    private array $mapper_functions;

    public function __construct(?string $name = null, int $indentation = 0)
    {
        parent::__construct(null, $name ?? '', $indentation);
    }

    public function setPath(string $path) : self
    {
        $this->path = $path;
        return $this;
    }

    public function getPath() : ?string
    {
        return $this->path;
    }

    public function isStrictTypes(): bool
    {
        return $this->strictTypes;
    }

    public function setStrictTypes(bool $strictTypes): self
    {
        $this->strictTypes = $strictTypes;
        return $this;
    }

    public function isAddGeneratedMarker(): bool
    {
        return $this->addGeneratedMarker;
    }

    public function setAddGeneratedMarker(bool $addGeneratedMarker): self
    {
        $this->addGeneratedMarker = $addGeneratedMarker;
        return $this;
    }

    public function writeToFile(?string $filePath = null): bool
    {
        if ($filePath === null) {
            if ($this->getPath() !== null) {
                $filePath = $this->getPath();
            } else {
                throw new \Exception('No path given to write file');
            }
        }

        $dir = dirname($filePath);

        if (!is_dir($dir) && !mkdir($dir, 777, true) && !is_dir($dir)) {
            throw new \RuntimeException("Directory '$dir' was not created");
        }

        return file_put_contents($filePath, $this->generateCode()) !== false;
    }

    public function addFunction(string $name): CGFunction
    {
        $func = new CGFunction($this, $name, $this->indentation);

        $this->code[] = $func;

        return $func;
    }

    // TODO: Implement this better function
//    public function addClass(string $name): CGClass
//    {
//        $this->classes[] = new CGClass($this, $name, $this->indentation);
//        $this->code[] = "<<CLASS_REPRESENTATION_$name>>";
//
//        $classKey = array_key_last($this->classes);
//        $codeKey = array_key_last($this->code);
//
//        $this->mapper_classes[] = [$codeKey, $classKey];
//
//        return $this->classes[$classKey];
//    }

    public function addClass(string $name) : CGClass
    {
        $cg = new CGClass($this, $name, $this->indentation);

        $this->code[] = $cg;

        return $cg;
    }

    public function findClass(string $name) : CGClass
    {
        /** @var ?CGClass $res */
        $res = $this->searchFor(CGClass::class, $name);

        if ($res)
            return $res;

        throw new \Exception("Class named $name not found in CGFile");
    }

    public function findFunction(string $name) : CGFunction
    {
        /** @var ?CGFunction $res */
        $res = $this->searchFor(CGFunction::class, $name);

        if ($res)
            return $res;

        throw new \Exception('Function not found');
    }

    public function findClassNoThrow(string $name) : ?CGClass
    {
        /** @var ?CGClass $res */
        $res = $this->searchFor(CGClass::class, $name);

        if ($res)
            return $res;

        return null;
    }

    public function findFunctionNoThrow(string $name) : ?CGFunction
    {
        /** @var ?CGFunction $res */
        $res = $this->searchFor(CGFunction::class, $name);

        if ($res)
            return $res;

        return null;
    }

    private function searchFor(string $classType, string $name) : ?object
    {
        foreach ($this->code as $codeline) {
            if (is_object($codeline) && $codeline instanceof $classType && $codeline->getName() === $name) {
                return $codeline;
            }
        }

        return null;
    }

    public function generateCode(): string
    {
        $codeBackUp = $this->code;
        $this->clearCode();

        $this
            ->addCodeLine('<?php')
            ->addCodeLineIf($this->isStrictTypes(), 'declare(strict_types=1);')
            ->addBlank()
            ->addCodeBlockIf($this->isAddGeneratedMarker(), $this->getAutoGeneratedMaker())
            ->addCodeLineIf($this->hasNameSpace(), "namespace {$this->getNameSpace()};")
            ->addBlankIf($this->hasNameSpace());

        if (!empty($this->hasUseStatements())) {
            $uses = array_unique($this->getUseStatements());

            foreach ($uses as $use) {
                $this->addCodeLine("use $use;");
            }

            $this->addBlank();
        }

        $final_code = $this->code;
        $this->code = $codeBackUp;

        return implode("\n", array_merge($final_code, $this->code));
    }

    /**
     * @return static
     */
    public function clearUseStatements() : self
    {
        $this->useStatements = [];
        return $this;
    }

    /**
     * @param string $use
     * @return static
     */
    public function addUseStatement(string $use) : self
    {
        $this->useStatements[] = $use;
        return $this;
    }

    public function hasUseStatements() : bool
    {
        return !empty($this->useStatements);
    }

    public function getUseStatements() : array
    {
        return $this->useStatements;
    }

    public function setNamespace(string $namespace) : self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getNameSpace() : ?string
    {
        return $this->namespace;
    }

    public function hasNameSpace() : bool
    {
        return ($this->namespace !== null && $this->namespace !== '');
    }

    protected function getAutoGeneratedMaker() : string
    {
        $now = (new \DateTime())->format('Y-m-d') ;
        $code[] = '//************************************/';
        $code[] = "//* Auto generated on: $now";
        $code[] = '//************************************/';
        $code[] = '';

        return implode("\n", $code);
    }
}