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
    public function getUser(string $user, string $password) {

      $this->connection = new \mysqli($this->server_name, $this->db_name, $this->db_password, $this->database);
      // Check connection
      if ($this->connection->connect_errno) {
        printf("Connect failed: %s\n", $this->connection->connect_error);
        exit();
      }
        
        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = mysqli_stmt_init($this->connection);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
          throw new Exception('Not a valid sql');
          exit();
        } else {
          mysqli_stmt_bind_param($stmt, 's', $user);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if ($row = mysqli_fetch_assoc($result)) {
            $pwdCheck = $password === $row['password'];
            if ($pwdCheck == false) {
              throw new \WrongPassword('Wrong name or password');
              exit();
            }
            else if ($pwdCheck == true) {
              return true;
            }
          }
        }


        // $result = $this->connection->query($sql);
        // $numRows = $result->num_rows;
        // // return $result;
        // if ($numRows > 0) {
        //     while($row = $result->fetch_assoc()) {
        //         $data[] = $row;
        //     }
        //     return $data[0]['username'];
        // }
    }
}