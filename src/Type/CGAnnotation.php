<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\HasNoNamePassedInConstructor;
use Inflyter\CodeGenerator\Traits\HasUsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\HasUsesNamespaceFromParentTrait;

class CGAnnotation extends AbstractCGType
{
    use HasUsesUseStatementsFromParentTrait;
    use HasUsesUseStatementsFromParentTrait;
    use HasUsesNamespaceFromParentTrait;
    use HasNoNamePassedInConstructor;

    public function generateCode(): string
    {
        $codeBackUp = $this->code;
        $this->clearCode();

        $this
            ->addCodeLine('/**')
            ->addCodeBlock('* ' . implode("\n* ", $codeBackUp))
            ->addCodeLine('*/')
        ;

        return $this->getFormattedCode();
    }

    public function getAnnotation() : CGAnnotation
    {
        throw new \RuntimeException('This type is not permitted to have annotations');
    }

    public function hasAnnotation() : bool
    {
        return false;
    }
}
