<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


interface CGTypeInterface
{
    public function generateCode() : string;
    public function __toString() : string;

    public function end() : AbstractCGType;

    public function clearUseStatements(): object;
    public function addUseStatement(string $use) : object;
    public function hasUseStatements() : bool;
    public function getUseStatements() : array;

    public function setNamespace(string $namespace) : object;
    public function getNameSpace() : ?string;
    public function hasNameSpace() : bool;

    public function hasCode() : bool;
    public function clearCode() : self;

    public function addCodeLine(string $line) : self;
    public function addCodeLineIf(bool $cond, string $line) : self;
    public function addCodeLineRepeated(string $line, int $times = 1) : self;
    public function addCodeToLastLine(string $text) : self;
    public function addCodeBlock(string $code) : self;
    public function addCodeBlockIf(bool $cond, string $code) : self;

    public function indent(int $indentationAmount = 1) : self;
    public function unindent(int $indentationAmount = 1) : self;
    public function setIndentation(int $indentationAmount) : self;
    public function getIndentationAmount() : int;

    public function addScopeOpen() : self;
    public function addScopeClose() : self;

    public function addBlank(int $blanks = 1) : self;
    public function addBlankIf(bool $cond, int $blanks = 1) : self;

    public function setParent(AbstractCGType $parent) : self;
    public function getParent() : ?self;
    public function hasParent() : bool;

    public function setName(string $name) : self;
    public function getName() : ?string;
    public function hasName() : bool;

    /**
     * @param string $text
     * @return static
     */
    public function addTextToAnnotation(string $text);
    public function hasAnnotation() : bool;
    public function getAnnotation() : ?CGAnnotation;
}
