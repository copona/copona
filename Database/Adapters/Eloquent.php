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
    public function __construct(Array $configs, \Registry $registry)
    {
        $capsule = new Capsule;

        //Register connections
        foreach ($configs['connections'] as $name => $connection) {
            $capsule->addConnection([
                'driver'    => isset($connection['db_driver']) ? $connection['db_driver'] : 'mysql',
                'host'      => $connection['db_hostname'],
                'database'  => $connection['db_database'],
                'username'  => $connection['db_username'],
                'password'  => $connection['db_password'],
                'charset'   => isset($connection['db_charset']) ? $connection['db_charset'] : 'utf8',
                'collation' => isset($connection['db_collation']) ? $connection['db_collation'] : 'utf8_unicode_ci',
                'prefix'    => isset($connection['db_prefix']) ? $connection['db_prefix'] : NULL,
                'port'      => isset($connection['db_port']) ? $connection['db_port'] : '3306'
            ], $name);
        }

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
        $this->capsule = $capsule;
        $this->connection = $this->capsule->getConnection(\Config::get('connection_name'));
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