<?php

namespace Login\Model;

include_once("Exceptions.php");

class Authentication
{
  private $storage;
  private $db;

  public function __construct(\Login\Model\UserStorage $storage)
  {
    $this->storage = $storage;
    $this->db =  new \Login\Model\Database();
  }

  public function tryToSaveUser($credentials): void
  {
    $userCheck = $this->db->isAValidUser($credentials);

    if ($userCheck) {
      $this->storage->saveUser($credentials);
      $this->saveCookie($credentials);
      $this->storage->setIsLoggedIn(true);
    } else {
      throw new \WrongPasswordOrUsername("Wrong name or password");
    }
  }

  private function saveCookie($credentials): void
  {
    if ($credentials->getKeepLoggedIn()) {
      $name = $credentials->getName();
      $password = $credentials->getPassword();

      setcookie('username', $name, time() + 2000, "", "", false, true);

      setcookie('password', $password, time() + 2000, "", "", false, true);
    }
  }

  public function removeCookie(): void
  {
    setcookie('username', '', time() - 3000);
    setcookie('password', '', time() - 3000);
  }

  public function getUserByCookie(): \Login\Model\UserModel
  {
    $name = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    return new \Login\Model\UserModel($name, $password, true);
  }

  public function hasCookie(): bool
  {
    return isset($_COOKIE['username']) && isset($_COOKIE['password']);
  }
}
