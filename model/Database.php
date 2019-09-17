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
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        $userCheck = $row['username'] === $user;
        $pwdCheck = $password === $row['password'];

        if ($pwdCheck == false || $userCheck == false) {
          throw new \WrongPassword('Wrong name or password');
        } else {
          return true;
        }

        // $stmt = mysqli_stmt_init($this->connection);
        // if(!mysqli_stmt_prepare($stmt, $sql)) {
        //   throw new \Exception('Not a valid sql');
        //   exit();
        // } else {
        //   mysqli_stmt_bind_param($stmt, 's', $user);
        //   mysqli_stmt_execute($stmt);
        //   $result = mysqli_stmt_get_result($stmt);
        //   if ($row = mysqli_fetch_assoc($result)) {

        //     $userCheck = $row['username'] === $user;
        //     $pwdCheck = $password === $row['password'];

        //     if ($pwdCheck == false || $userCheck == false) {
        //       throw new \WrongPassword('Wrong name or password');
        //       return false;
        //     }
        //     else if ($pwdCheck == true) {
        //       return true;
        //     } else {
        //       return false;
        //     }
        //   }
        //   return false;
        // }


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