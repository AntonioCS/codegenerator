<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait HasTypeTrait
{
    private ?string $type = null;

    private bool $isNull = false;

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return static
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function hasType() : bool
    {
        return $this->type !== null && $this->type !== '';
    }

    public function isNull(): bool
    {
        return $this->isNull;
    }

    /**
     * @param bool $isNull
     * @return static
     */
    public function setIsNull(bool $isNull): self
    {
        $this->isNull = $isNull;
        return $this;
    }
}
