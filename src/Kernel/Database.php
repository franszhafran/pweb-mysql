<?php

namespace App\Kernel;

class Database {
    public function __construct() {
        $this->ip = "pweb-mysql_pweb_mysql_1";
        $this->username = "user";
        $this->password = "user";
        $this->database = "laravel_docker";
    }

    public static function init(): Database {
        $r = new self;
        $r->connect();
        return $r;
    }

    public function connect() {
        $conn = new \mysqli($this->ip, $this->username, $this->password, $this->database);
        $this->connection = $conn;
    }

    public function query($query) {
        return $this->connection->query($query);
    }

    public function close() {
        $this->connection->close();
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>