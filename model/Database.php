<?php

namespace Login\Model;

include_once("Exceptions.php");

class Database extends DatabaseConfig {
    private $connection;
    private $userCheck;
    private $pwdCheck;

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
    public function isAValidUser(UserModel $credentials) {
      $username = $credentials->getName();
      $password = $credentials->getPassword();

      $this->connection = new \mysqli($this->server_name, $this->db_name, $this->db_password, $this->database);
      // Check connection
      if ($this->connection->connect_errno) {
        printf("Connect failed: %s\n", $this->connection->connect_error);
        exit();
      }
      
        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        $this->userCheck = $row['username'] === $username;
        $this->pwdCheck = $password === $row['password'];

        if ($this->pwdCheck == false || $this->userCheck == false) {
          throw new \WrongPasswordOrUsername("Wrong name or password");
        } else {
          return true;
        }
    }

    public function checkExceptions(UserModel $credentials) {
      if ($this->pwdCheck == false || $this->userCheck == false) {
         throw new WrongPasswordOrUsername("Wrong name or password");
      }
    }
}