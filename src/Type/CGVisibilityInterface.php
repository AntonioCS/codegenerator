<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Type;


interface CGVisibilityInterface
{
    public function setVisibilityProtected() : static;

    public function setVisibilityPrivate() : static;

    public function setVisibilityPublic() : static;

    public function getVisibility() : string;

}