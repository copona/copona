<?php

namespace Copona\Database;

use Symfony\Component\Config\Definition\Exception\Exception;

class Database
{
    /**
     * @var AbstractDatabaseAdapters
     */
    private $adapter;

    public function __construct($adapter, Array $configs, \Registry $registry)
    {
        if (get_parent_class($adapter) == AbstractDatabaseAdapters::class) {
            $this->adapter = new $adapter($configs, $registry);
        } else {
            throw new Exception($adapter . ' must extends of ' . AbstractDatabaseAdapters::class);
        }
    }

    public function getAdapter()
    {
        return $this->adapter;
    }
}