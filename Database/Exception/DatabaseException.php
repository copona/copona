<?php

namespace Copona\Database\Exception;


class DatabaseException extends \Exception
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
        $errorInfo = $e->errorInfo;
        if (isset($errorInfo[2])) {
            $this->message = $errorInfo[2];
        }

        if ($this->sql) {
            $this->message .= ' - SQL query: ' . $this->sql;
        }
    }
}