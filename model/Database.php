<?php

namespace Login\Model;

class Database extends DatabaseConfig {
    private $connection;

  // Connect to database with secure config data.
  public function connect() {
    
    $this->connection = new mysqli($this->server_name, $this->db_name, $this->db_password, $this->database);

    // Check connection
    if ($this->connection->connect_error) {
      die("Connection failed: " . $this->connection->connect_error);
    }
     return $this->connection;
  }

    // Check if user exists in database
    public function getUser() {
        $query_user = "SELECT * FROM users";
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