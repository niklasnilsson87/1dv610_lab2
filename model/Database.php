<?php

namespace Login\Model;

include_once("Exceptions.php");

class Database extends DatabaseConfig {
    private $connection;

  // Connect to database with secure config data.
  // private function connect() {
    
  //   $this->connection = new \mysqli($this->server_name, $this->db_name, $this->db_password, $this->database);
  //   // Check connection
  //   if ($this->connection->connect_errno) {
  //     printf("Connect failed: %s\n", $this->connection->connect_error);
  //     exit();
  //   }
  //    return $this->connection;
  // }

    // Check if user exists in database
    public function isAValidUser(string $user, string $password) {

      $this->connection = new \mysqli($this->server_name, $this->db_name, $this->db_password, $this->database);
      // Check connection
      if ($this->connection->connect_errno) {
        printf("Connect failed: %s\n", $this->connection->connect_error);
        exit();
      }
      
        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        $userCheck = $row['username'] === $user;
        $pwdCheck = $password === $row['password'];

        if ($pwdCheck == false || $userCheck == false) {
          throw new \WrongPasswordOrUsername('Wrong name or password');
        } else {
          return true;
        }
    }
}