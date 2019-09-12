<?php

class Database {
    private $connection;

    public function connect() {
        $this->connection = new mysqli(Config::$SERVER_NAME, Config::$DATABASE_USERNAME, Config::$DATABASE_PASSWORD, Config::$DATABASE_NAME);
        return $this->connection;
    }
}