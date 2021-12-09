<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait HasTypeTrait
{
    private array $types = [];

    private bool $isNull = false;

    public function getTypes(): string
    {
        return implode('|', $this->types);
    }

    public function hasMultipleTypes() : bool
    {
        return count($this->types) > 1;
    }

    public function setTypes(?string $types): self
    {
        if ($types) {
            $allTypes = explode('|', $types);

            foreach ($allTypes as $type) {
                $this->types[] = $type;
            }
        }

        return $this;
    }

    public function addType(string $type) : static
    {
        $this->types[] = $type;
        return $this;
    }

    public function hasType() : bool
    {
        return !empty($this->types);
    }

    public function isNull(): bool
    {
        return $this->isNull;
    }

    public function setIsNull(bool $isNull): static
    {
        $this->isNull = $isNull;
        return $this;
    }
}
