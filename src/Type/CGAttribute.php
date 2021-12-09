<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


use Inflyter\CodeGenerator\Traits\HasNoNamePassedInConstructor;
use Inflyter\CodeGenerator\Traits\HasNotPermittedToHaveAnnotations;
use Inflyter\CodeGenerator\Traits\HasUsesNamespaceFromParentTrait;
use Inflyter\CodeGenerator\Traits\HasUsesUseStatementsFromParentTrait;

class CGAttribute extends AbstractCGType
{
    use HasUsesUseStatementsFromParentTrait;
    use HasUsesUseStatementsFromParentTrait;
    use HasUsesNamespaceFromParentTrait;
    use HasNotPermittedToHaveAnnotations;
    use HasNoNamePassedInConstructor;

    /** @var object[] */
    private array $attributes;

    public function generateCode(): string
    {
        $this->addCodeLine('#[');
        foreach ($this->attributes as $k => $attribute) {
            if ($k !== 0) {
                $this->addCodeToLastLine(', ');
            }

            $attributeClassFullPath = get_class($attribute);
            $attributeName = $this->getClassName($attributeClassFullPath);
            $data = get_object_vars($attribute);
            $this->addCodeToLastLine($attributeName);

            if (!empty($data) && $this->dataIsNotAllNull($data)) {
                $this->addCodeToLastLine('(');
                $this->processData($data);
                $this->addCodeLine(')');
            }
        }

        return $this->unindent()
                    ->addCodeToLastLine(']')
                    ->getFormattedCode();
    }

    private function dataIsNotAllNull(array $data) : bool
    {
        foreach ($data as $v) {
            if ($v !== null) {
                return true;
            }
        }

        return false;
    }

    //https://www.php.net/manual/en/function.get-class.php#114568
    private function getClassName(string $classFullPath) : string
    {
        if ($pos = strrpos($classFullPath, '\\')) {
            return substr($classFullPath, $pos + 1);
        }
        return $classFullPath;
    }


    public function addAttributeObject(object $attribute, bool $addUseStatement = true) : static
    {
        if ($addUseStatement) {
            $this->addUseStatement(get_class($attribute));
        }

        $this->attributes[] = $attribute;
        return $this;
    }

    private function processData(array $values, bool $insideArgs = false) : void
    {
        $this->indent();
        foreach ($values as $k => $v) {
            if ($v === null) {
                continue;
            }

            $isKeyString = is_string($k);
            if ($isKeyString) {
                if ($insideArgs) {
                    $this->surroundWithSingleQuotes($k);
                }
                if ($this->isPreviousCodeLineEmpty()) {
                    $this->addCodeToLastLine($k);
                } else {
                    $this->addCodeLine($k);
                }
            }

            switch (true) {
                case is_array($v):
                    $extra = '';
                    if ($isKeyString) {
                        $extra = ($insideArgs ? ' => ' :  ': ');
                    }
                    $this->addCodeToLastLine("{$extra}[")
                        ->indent();

                    $this->processData($v, true);

                    $this->unindent(2)
                        ->addCodeLine(']');
                    break;
                case is_string($v):
                    $this->surroundWithSingleQuotes($v);
                default:
                    if (!$insideArgs) {
                        $this->addCodeToLastLine(" : $v");
                    } elseif ($isKeyString) {
                        $this->addCodeToLastLine(" => $v");
                    } elseif ($k === 0) {
                        $this->addCodeLine($v);
                    } else {
                        $this->addCodeToLastLine($v);
                    }
            }

            $this->addCodeToLastLine(',');
        }
    }

    private function surroundWithSingleQuotes(string &$str) : void
    {
        $str = "'$str'";
    }

}