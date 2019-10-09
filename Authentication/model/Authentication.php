<?php

namespace Login\Model;

include_once("Exceptions.php");

class Authentication
{
  private $storage;
  private $db;
  private $cookie;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Cookie $cookie)
  {
    $this->storage = $storage;
    $this->cookie = $cookie;
    $this->db =  new \Login\Model\Database();
  }

  public function tryToSaveUser($credentials): void
  {
    $userCheck = $this->db->isAValidUser($credentials);

    if ($userCheck) {
      $this->storage->saveUser($credentials);
      $this->cookie->saveCookie($credentials);
      $this->storage->setIsLoggedIn(true);
    } else {
      throw new \WrongPasswordOrUsername;
    }
  }

  public function doesUserExist($credentials)
  {
    $name = $credentials->getName();
    if ($this->db->userExist($name)) {
      throw new \UserAlreadyExist;
    } else {
      return false;
    }
  }

  public function register($credentials)
  {
    $this->db->registerUser($credentials);
    $this->storage->saveUser($credentials);
  }
}