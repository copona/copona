<?php

namespace Copona\Database\Exception;


class DatabaseException extends \PDOException
{
    private $sql = null;

    /**
     * @return null
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param null $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function __construct(\PDOException $e, $sql = null)
    {
        $this->sql = $sql;

        if ($e instanceof \PDOException) {
            $this->preparePDOException($e);
        }
    }

    protected function preparePDOException(\PDOException $e)
    {
        $this->message = $e->getMessage();
        $this->code = $e->getCode();
        $this->file = $e->getFile();
        $this->line = $e->getLine();

        if ($this->sql) {
            $this->message .= ' - SQL query: ' . $this->sql;
        }
    }
}