<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\UsesUseStatementsFromParentTrait;
use Inflyter\CodeGenerator\Traits\UsesNamespaceFromParentTrait;

class CGAnnotation extends AbstractCGType
{
    use UsesUseStatementsFromParentTrait;
    use UsesUseStatementsFromParentTrait;
    use UsesNamespaceFromParentTrait;

    public function __construct(CGTypeInterface $parent, int $indentation = 0)
    {
        parent::__construct($parent, '', $indentation);
        $this->mustHaveParent();
    }

    public function generateCode(): string
    {
        $codeBackUp = $this->code;
        $this->clearCode();

        $this
            ->addCodeLine('/**')
            ->addCodeBlock('* ' . implode("\n* ", $codeBackUp))
            ->addCodeLine('*/')
        ;

        return implode("\n", $this->code);
    }

    public function getAnnotation() : CGAnnotation
    {
        throw new \Exception('This type is not permitted to have annotations');
    }

    public function hasAnnotation() : bool
    {
        return false;
    }
}
