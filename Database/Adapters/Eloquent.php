<?php

namespace Copona\Database\Adapters;

use Copona\Database\Exception\DatabaseException;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Copona\Database\AbstractDatabaseAdapters;

class Eloquent extends AbstractDatabaseAdapters
{
    /**
     * @var Capsule
     */
    private $capsule;

    /**
     * Connection name
     * @var string
     */
    private $name = 'default';

    /**
     * @var \Illuminate\Database\Connection
     */
    private $connection;

    /**
     * @var int
     */
    private $countAffected = 0;

    /**
     * Eloquent constructor.
     * @param array $configs
     */
    public function __construct(Array $configs)
    {
        if (isset($configs['connection_name'])) {
            $this->name = $configs['connection_name'];
        }

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => isset($configs['db_driver']) ? $configs['db_driver'] : 'mysql',
            'host'      => $configs['db_hostname'],
            'database'  => $configs['db_database'],
            'username'  => $configs['db_username'],
            'password'  => $configs['db_password'],
            'charset'   => isset($configs['db_charset']) ? $configs['db_charset'] : 'utf8',
            'collation' => isset($configs['db_collation']) ? $configs['db_collation'] : 'utf8_unicode_ci',
            'prefix'    => isset($configs['db_prefix']) ? $configs['db_prefix'] : NULL,
            'port'      => isset($configs['db_port']) ? $configs['db_port'] : '3306'
        ], $this->name);

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
        $this->capsule = $capsule;
        $this->connection = $this->capsule->getConnection($this->name);
    }

    /**
     * @param       $sql
     * @param array $params
     * @return bool|int|\stdClass
     * @throws DatabaseException
     */
    public function query($sql, Array $params = [])
    {
        try {

            $return = $this->getConnection()->getPdo()->query($sql);
            $data = $return->fetchAll();
            $result = new \stdClass();
            $result->num_rows = $return->rowCount();
            $result->row = isset($data[0]) ? $data[0] : array();
            $result->rows = $data;
            $this->countAffected = $return->rowCount();

            return $result;

        } catch (\PDOException $e) {
            throw new DatabaseException($e, $sql);
        }
    }

    /**
     * @param $sql
     * @return int
     */
    public function execute($sql)
    {
        return $this->getConnection()->affectingStatement($sql);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function escape($value)
    {
        return $value;
//        return $this->connection->getPdo()->quote($value);
    }

    /**
     * @return int
     */
    public function countAffected()
    {
        return $this->countAffected;
    }

    /**
     * @return string
     */
    public function getLastId()
    {
        return $this->getConnection()->getPdo()->lastInsertId();
    }

    /**
     * @param string $name
     * @return \Illuminate\Database\Connection
     */
    public function getConnection($name = null)
    {
        $name = ($name) ? $name : $this->name;
        return $this->capsule->getConnection($name);
    }

    /**
     * @return bool
     */
    public function connected()
    {
        if ($this->getConnection()->getDatabaseName()) {
            return true;
        } else {
            return false;
        }
    }
}