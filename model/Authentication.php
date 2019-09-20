<?php

namespace Login\Model;

include_once("Exceptions.php");

class Authentication
{
  private $storage;
  private $db;
  private $lv;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Database $db, \Login\View\LoginView $lv)
  {
    $this->storage = $storage;
    $this->db = $db;
    $this->lv = $lv;
  }

  public function checkCorrectCredentials($credentials): bool
  {
    $userCheck = $this->db->isAValidUser($credentials);

    if ($userCheck) {
      $this->storage->saveUser($credentials);
      $this->saveCookie($credentials);
      $this->storage->setIsLoggedIn(true);
      return true;
    } else {
      throw new \WrongPasswordOrUsername("Wrong name or password");
      return false;
    }
  }

  private function saveCookie($credentials)
  {
    // $option = array(
    //   'samesite'
    //   'HttpOnly' => true
    // );

    $name = $credentials->getName();
    $password = $credentials->getPassword();

    setcookie('username', $name, time() + 2000, "", "", false, true);

    setcookie('password', $password, time() + 2000, "", "", false, true);
  }

  public function removeCookie()
  {
    setcookie('username', '', time() - 3000);
    setcookie('password', '', time() - 3000);
  }

  public function getUserByCookie()
  {
    $name = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    return new \Login\Model\UserModel($name, $password, true);
  }

  public function hasCookie()
  {
    return isset($_COOKIE['username']) && isset($_COOKIE['password']);
  }
}
