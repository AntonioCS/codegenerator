<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait HasVisibilityTrait
{
    private string $visibility = 'public';

    public function setVisibilityProtected() : static
    {
        return $this->setVisibility('protected');
    }

    public function setVisibilityPrivate() : static
    {
        return $this->setVisibility('private');
    }

    public function setVisibilityPublic() : static
    {
        return $this->setVisibility('public');
    }

    public function getVisibility() : string
    {
        return $this->visibility;
    }

    private function setVisibility(string $type) : static
    {
        $this->visibility = $type;
        return $this;
    }
}