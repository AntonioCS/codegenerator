<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


interface CGVisibilityInterface
{
    /**
     * @return static
     */
    public function setVisibilityProtected() : self;

    /**
     * @return static
     */
    public function setVisibilityPrivate() : self;

    /**
     * @return static
     */
    public function setVisibilityPublic() : self;

    public function getVisibility() : string;

}