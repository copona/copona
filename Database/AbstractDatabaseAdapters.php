<?php

namespace Copona\Database;


abstract class AbstractDatabaseAdapters
{
    abstract public function __construct(Array $configs);

    abstract public function query($sql, Array $params);

    abstract public function escape($value);

    abstract public function countAffected();

    abstract public function getLastId();

    abstract public function connected();
}