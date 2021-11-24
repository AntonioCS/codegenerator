<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


interface CGVisibilityInterface
{
    /**
     * @return static
     */
    public function setVisibilityProtected() : static;

    /**
     * @return static
     */
    public function setVisibilityPrivate() : static;

    /**
     * @return static
     */
    public function setVisibilityPublic() : static;

    public function getVisibility() : string;

}