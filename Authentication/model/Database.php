<?php

namespace Login\Model;

include_once("LocalSettings.php");
include_once("ProductionSettings.php");

class Database
{
  private $connection;
  private $settings;

  private static $username = 'username';
  private static $password = 'password';

  private static $LOCALHOST = 'localhost';

  public function __construct()
  {
    $serverAdress = $_SERVER['SERVER_NAME'];
    if ($serverAdress == self::$LOCALHOST) {
      $this->settings = new \Login\Model\LocalSettings();
    } else {
      $this->settings = new \Login\Model\ProductionSettings();
    }

    $this->connection = new \mysqli(
      $this->settings->server_name,
      $this->settings->db_name,
      $this->settings->db_password,
      $this->settings->database
    );
  }

  public function isAValidUser(UserModel $credentials)
  {
    $username = $credentials->getName();
    $password = $credentials->getPassword();

    $row = $this->sqlCheck($username);

    $userCheck = $row[self::$username] === $username;
    $pwdCheck = password_verify($password, $row[self::$password]);

    if ($pwdCheck == false || $userCheck == false) {
      return false;
    } else {
      return true;
    }
  }

  public function userExist(string $name)
  {
    $result = $this->sqlCheck($name);
    if ($result[self::$username] === $name) {
      return true;
    }
    return false;
  }

  private function sqlCheck(string $username): ?array
  {
    $sql = "SELECT * FROM users WHERE username=?;";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
  }

  public function registerUser(\Login\Model\RegistrationUser $credentials): void
  {
    $name = $credentials->getName();
    $password = $credentials->getUserPassword();

    $options = [
      'cost' => 12
    ];

    $hash = password_hash($password, PASSWORD_BCRYPT, $options);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('ss', $name, $hash);
    $stmt->execute();
  }
}
