<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;



abstract class AbstractCGType implements CGTypeInterface
{
    public const DEFAULT_INDENTATION_AMOUNT = 4;

    protected int $indentation;

    private ?AbstractCGType $parent = null;

    protected ?string $name = null;

    /**
     * @var string[]|AbstractCGType[]
     */
    protected array $code = [];

    private ?CGAnnotation $annotation = null;

    public function __construct(?AbstractCGType $parent, string $name, int $indentation = 0)
    {
        if ($parent)
            $this->setParent($parent);

        $this->setName($name);

        $this->indentation = $indentation;
    }

    public function setParent(AbstractCGType $parent) : AbstractCGType
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent() : ?AbstractCGType
    {
        return $this->parent;
    }

    public function hasParent() : bool
    {
        return $this->parent !== null;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name) : self
    {
        $this->name = str_replace(' ', '',
            ucwords(
                str_replace(['_', '-'], ' ',
                    $this->removedAllNonAsciiChars($name)
                )
            )
        );

        return $this;
    }

    //https://stackoverflow.com/a/3371773/8715
    //https://stackoverflow.com/a/60816979/8715
    private function removedAllNonAsciiChars(string $value) : string
    {
        $curLocale = setlocale(LC_ALL, 0); //gets current locale
        setlocale(LC_ALL, "en_US.utf8"); //without this iconv removes accented letters. If you use another locale it will also fail

        $result = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

        setlocale(LC_ALL, $curLocale);

        return $result;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function hasName() : bool
    {
        return $this->name !== null;
    }

    /**
     * @return static
     * @throws \Exception
     */
    public function end() : self
    {
        $this->mustHaveParent();
        return $this->parent;
    }

    abstract public function generateCode(): string;

    public function __toString(): string
    {
        return $this->generateCode();
    }

    public function hasCode() : bool
    {
        return empty($this->code) === false;
    }

    /**
     * @return static
     */
    public function clearCode() : self
    {
        $this->code = [];
        return $this;
    }

    /**
     * @param string $line
     * @return static
     */
    public function addCodeLine(string $line) : self
    {
        $this->code[] = ($this->indentation > 0 ? str_repeat(' ', $this->indentation) : '') . $line;
        return $this;
    }

    public function addForeachStart(string $iterableExpression, string $value, ?string $key = null) : self {
        return $this
            ->addCodeLine("foreach ($iterableExpression as " . ($key ? "$key => " : '') . "$value) {")
            ->indent()
        ;
    }

    public function addForeachEnd() : self
    {
        return $this->unindent()->addCodeLine('}');
    }

    public function addCodeLineIf(bool $cond, string $line) : self
    {
        if ($cond) {
            $this->addCodeLine($line);
        }
        return $this;
    }

    public function addCodeLineRepeated(string $line, int $times = 1) : self
    {
        if ($times > 0) {
            while ($times--)
                $this->addCodeLine($line);
        }

        return $this;
    }

    public function addCodeToLastLine(string $text) : self
    {
        $last = count($this->code) - 1;
        $this->code[$last] .= $text;
        return $this;
    }

    public function addCodeBlock(string $code) : self
    {
        $lines = explode("\n", $code);
        foreach ($lines as $line) {
            $this->addCodeLine($line);
        }

        return $this;
    }

    public function addCodeBlockIf(bool $cond, string $code) : self
    {
        if ($cond) {
            $this->addCodeBlock($code);
        }

        return $this;
    }

    public function addComment(string $text) : self
    {
        return $this->addCodeLine("// $text");
    }

    public function addCommentIf(bool $cond, string $text) : self
    {
        return $this->addCodeLineIf($cond, "// $text");
    }

    public function indent(int $indentationAmount = 1) : self
    {
        while ($indentationAmount--)
            $this->indentation += self::DEFAULT_INDENTATION_AMOUNT;
        return $this;
    }

    public function indentIf(bool $cond, int $indentationAmount = 1): self
    {
        if ($cond) {
            return $this->indent($indentationAmount);
        }

        return $this;
    }

    public function unindent(int $indentationAmount = 1) : self
    {
        while ($indentationAmount--)
            $this->indentation -= self::DEFAULT_INDENTATION_AMOUNT;
        return $this;
    }

    public function unindentIf(bool $cond,int $indentationAmount = 1) : self
    {
        if ($cond) {
            return $this->unindent($indentationAmount);
        }

        return $this;
    }

    public function setIndentation(int $indentationAmount) : self
    {
        $this->indentation = $indentationAmount;
        return $this;
    }

    public function getIndentationAmount() : int
    {
        return $this->indentation;
    }

    public function addScopeOpen() : self
    {
        $this->addCodeLine('{');
        $this->indent();

        return $this;
    }
    public function addScopeClose() : self
    {
        $this->unindent();
        $this->addCodeLine('}');
        return $this;
    }

    public function addBlank(int $blanks = 1) : self
    {
        while ($blanks--) {
            $this->addCodeLine('');
        }

        return $this;
    }

    public function addBlankIf(bool $cond, int $blanks = 1) : self
    {
        if ($cond) $this->addBlank($blanks);
        return $this;
    }

    /**
     * @param string $text
     * @return static
     */
    public function addTextToAnnotation(string $text)
    {
        if ($this->hasAnnotation() === false) {
            $this->annotation = new CGAnnotation($this, $this->getIndentationAmount());
        }

        $this->annotation->addCodeLine($text);

        return $this;
    }

    public function hasAnnotation() : bool
    {
        return $this->annotation !== null;
    }

    public function getAnnotation() : ?CGAnnotation
    {
        if ($this->hasAnnotation()) {
            return $this->annotation;
        }

        return null;
    }

    protected function processAnnotation() : void
    {
        if ($this->hasAnnotation()) {
            $this->addCodeBlock($this->annotation->generateCode());
        }
    }

    protected function mustHaveParentAndName() : void
    {
        $this->mustHaveParent();
        $this->mustHaveName();
    }

    protected function mustHaveParent() : void
    {
        if ($this->getParent() === null) {
            throw new \Exception('A parent must be set in ' . get_class($this));
        }

    }

    protected function mustHaveName() : void
    {
        if ($this->getName() === null) {
            throw new \Exception('A name must be set in ' . get_class($this));
        }
    }

//    WIP
//    public function addArray(string $name, array $data) : CGArray
//    {
//        $a = new CGArray($this, $name, $this->getIndentationAmount());
//        $a->setArray($data);
//        $code[] = $a;
//        return $a;
//    }

}