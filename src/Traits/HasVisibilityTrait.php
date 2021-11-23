<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Traits;


trait HasVisibilityTrait
{
    private string $visibility = 'public';

    /**
     * @return static
     */
    public function setVisibilityProtected() : self
    {
        return $this->setVisibility('protected');
    }

    /**
     * @return static
     */
    public function setVisibilityPrivate() : self
    {
        return $this->setVisibility('private');
    }

    /**
     * @return static
     */
    public function setVisibilityPublic() : self
    {
        return $this->setVisibility('public');
    }

    public function getVisibility() : string
    {
        return $this->visibility;
    }

    /**
     * @param string $type
     * @return static
     */
    private function setVisibility(string $type) : self
    {
        $this->visibility = $type;
        return $this;
    }
}