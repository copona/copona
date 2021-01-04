<?php

namespace DB;

final class MySQLi {
    private $connection;

    public function __construct($hostname, $username, $password, $database, $port = '3306') {

        $this->connection = mysqli_init();
        $timeout_seconds = 10;
        mysqli_options($this->connection, MYSQLI_OPT_CONNECT_TIMEOUT, $timeout_seconds);
        mysqli_real_connect(
            $this->connection,
            $hostname,
            $username,
            $password,
            $database,
            $port
        );

        if ($this->connection->connect_errno) {
            throw new \Exception('Error: ' . $this->connection->error . '<br />Error No: ' . $this->connection->errno);
        }

        $this->log = new \Log('mysql_queries.log');

        $this->connection->set_charset("utf8");
        $this->connection->query("SET SQL_MODE = ''");
    }

    public function query($sql) {

        if(\Config::get('debug.sql')) {
            $start_time = microtime(true);

            $this->log->write( "Started: (".substr( md5( $sql ), 0, 8) .") $sql" );
            $query = $this->connection->query($sql);

            $msec = number_format(microtime(true) - $start_time, 4, '.', ',') . " msec";
            $output = "Ended (".substr( md5( $sql ), 0, 8).") in: $msec" . " \t" . $sql . "\n";

            for ($i = 0; $i < 3; $i++) {
                    if(empty( debug_backtrace()[$i]['file']) ) {
                        break;
                    }
                    $sqls['files'][] = debug_backtrace()[$i]['file'] . ":" . debug_backtrace()[$i]['line'];
                    $output .= debug_backtrace()[$i]['file'] . ":" . debug_backtrace()[$i]['line'] . " \n";
                }

            $this->log->write( $output );
        } else {
            $query = $this->connection->query($sql);
        }

        if (!$this->connection->errno) {
            if ($query instanceof \mysqli_result) {
                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->rows = [];

                while ($row = $query->fetch_assoc()) {
					$result->rows[] = $row;
                }

                $result->row = isset($result->rows[0]) ? $result->rows[0] : [];

                $query->close();

                return $result;
            } else {
                return true;
            }
        } else {
            throw new \Exception('Error: ' . $this->connection->error . '<br />Error No: ' . $this->connection->errno . '<br />' . $sql);
        }
    }

    public function escape($value) {
        return $this->connection->real_escape_string($value);
    }

    public function countAffected() {
        return $this->connection->affected_rows;
    }

    public function getLastId() {
        return $this->connection->insert_id;
    }

    public function connected() {
        return $this->connection->ping();
    }

    public function __destruct() {
        $this->connection->close();
    }

}
