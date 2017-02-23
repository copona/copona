<?php

namespace DB;

final class MySQLi {
    private $connection;

    public function __construct($hostname, $username, $password, $database, $port = '3306') {
        $this->connection = new \mysqli($hostname, $username, $password, $database, $port);

        if ($this->connection->connect_error) {
            throw new \Exception('Error: ' . $this->connection->error . '<br />Error No: ' . $this->connection->errno);
        }

        $this->connection->set_charset("utf8");
        $this->connection->query("SET SQL_MODE = ''");

    }

    public function query($sql) {

        if(defined('MODE') && (MODE == 'debug')) {
            $start_time = microtime(true);

            $query = $this->connection->query($sql);

            $msec = number_format(microtime(true) - $start_time, 4, '.', ',') . " msec";

            $output = date("Y-m-d h:i:s"). " \t";
            $output .= $msec . " \t";
            $output .= $sql . " \n";

            if (!file_exists(DIR_LOGS . 'mysql_queries.txt')) {
                touch(DIR_LOGS . 'mysql_queries.txt');
            }

            $file = fopen(DIR_LOGS . 'mysql_queries.txt', 'a');

            fwrite($file, $output);

            fclose($file);

        } else {
            $query = $this->connection->query($sql);
        }

        if (!$this->connection->errno) {
            if ($query instanceof \mysqli_result) {
                $data = array();

                while ($row = $query->fetch_assoc()) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : array();
                $result->rows = $data;

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