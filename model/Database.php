<?php

class Database {
    private $connection;

    // Connect to database with secure config data.
    public function connect() {
        $this->connection = new mysqli(Config::$SERVER_NAME, Config::$DATABASE_USERNAME, Config::$DATABASE_PASSWORD, Config::$DATABASE_NAME);
        return $this->connection;
    }

    // Check if user exists in database
    public function getUser(string $username, string $password) {
        $query_user = "SELECT * FROM users WHERE username=$username";
        $result = query($query_user);
        $numRows = $result->num_rows;

        if ($numRows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
}