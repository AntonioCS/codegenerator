<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type\CGClass;

use Inflyter\CodeGenerator\Traits\HasEndClassReturn;
use Inflyter\CodeGenerator\Traits\HasStaticAccess;
use Inflyter\CodeGenerator\Traits\HasVisibilityTrait;
use Inflyter\CodeGenerator\Type\AbstractParameter;
use Inflyter\CodeGenerator\Type\CGVisibilityInterface;

class CGConst extends AbstractParameter implements CGVisibilityInterface
{
    use HasVisibilityTrait;
    use HasEndClassReturn;

    public function generateCode(): string
    {
        $this->noDollarSign = true;
        $this->ignoreType = true;

        $code = $this->getVisibility() . ' const ' . parent::generateCode() . ';';
        $this->clearCode();

        $this->processAnnotation();
        $this->processAttribute();

        if ($this->hasCode()) {
            $code = implode("\n", $this->code) . "\n" . $code;
        }

        return $code;
    }

    public function setName(string $name) : static
    {
        $this->name = str_replace(' ', '_', $this->removedAllNonAsciiChars($name));
        return $this;
    }
}
