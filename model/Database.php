<?php

class Database {
    private $connection;

    // Connect to database with secure config data.
  public function connect() {
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);

    $servername = $dbparts['host'];
    $dbName = $dbparts['user'];
    $dbPassword = $dbparts['pass'];
    $database = ltrim($dbparts['path'],'/');
    
    // Create connection
    $this->connection = new mysqli($servername, $dbName, $dbPassword, $database);

    // Check connection
    if ($this->connection->connect_error) {
      die("Connection failed: " . $this->connection->connect_error);
    }
     return $this->connection;
  }

    // Check if user exists in database
    public function getUser() {
        $query_user = "SELECT * FROM Loginsystem";
        $result = $this->connect()->query($query_user);
        $numRows = $result->num_rows;

        if ($numRows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
}