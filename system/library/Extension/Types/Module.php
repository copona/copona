<?php

namespace Metastore\System\Extension\Types;


use Metastore\System\Extension\TypeInterface;

class Module implements TypeInterface
{
    /**
     * Code the type
     *
     * @return string
     */
    public function code()
    {
        return 'module';
    }
}