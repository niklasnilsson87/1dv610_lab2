<?php

namespace Login\Model;

include_once("Exceptions.php");
include_once("LocalSettings.php");
include_once("ProductionSettings.php");

class Database
{
  private $connection;
  private $userCheck;
  private $pwdCheck;
  private $settings;

  public function __construct()
  {

    // Check if localhost
    $serverAdress = $_SERVER['SERVER_NAME'];
    if ($serverAdress == 'localhost') {
      $this->settings = new \Login\Model\LocalSettings();
    } else {
      $this->settings = new \Login\Model\ProductionSettings();
    }

    $this->connection = new \mysqli($this->settings->server_name, $this->settings->db_name, $this->settings->db_password, $this->settings->database);
    // Check connection
    if ($this->connection->connect_errno) {
      printf("Connect failed: %s\n", $this->connection->connect_error);
      exit();
    }
  }

  // Check if user exists in database
  public function isAValidUser(UserModel $credentials)
  {
    $username = $credentials->getName();
    $password = $credentials->getPassword();

    $row = $this->sqlCheck($username);

    $this->userCheck = $row['username'] === $username;
    $this->pwdCheck = $password === $row['password'];

    if ($this->pwdCheck == false || $this->userCheck == false) {
      return false;
    } else {
      return true;
    }
  }

  public function userExist(string $name)
  {
    $result = $this->sqlCheck($name);
    if ($result['username'] === $name) {
      return true;
    }
    return false;
  }

  public function sqlCheck($username)
  {
    $sql = "SELECT * FROM users WHERE username=?;";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
  }

  public function registerUser($credentials)
  {
    $name = $credentials->getName();
    $password = $credentials->getUserPassword()->getPassword();

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('ss', $name, $password);
    $stmt->execute();
  }
}
