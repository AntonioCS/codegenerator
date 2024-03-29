<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;

use Inflyter\CodeGenerator\Traits\HasEndClassReturn;
use Inflyter\CodeGenerator\Traits\HasStaticAccess;
use Inflyter\CodeGenerator\Traits\HasVisibilityTrait;
use Inflyter\CodeGenerator\Type\AbstractParameter;
use Inflyter\CodeGenerator\Type\CGVisibilityInterface;

class CGProperty extends AbstractParameter implements CGVisibilityInterface
{
    use HasVisibilityTrait;
    use HasEndClassReturn;
    use HasStaticAccess;

    public function generateCode(): string
    {
        $code = $this->getVisibility() . ' ' . ($this->getStaticAccess()) . parent::generateCode() . ';';
        $this->clearCode();

        $this->processAnnotation();
        $this->processAttribute();

        if ($this->hasCode()) {
            $code = implode("\n", $this->code) . "\n" . $code;
        }

        return $code;
    }
}
