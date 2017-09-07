<?php

namespace Copona\System\Engine;

use Copona\Database\ModelBase;

abstract class Model extends ModelBase
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
        parent::__construct($registry);
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }
}