<?php

namespace Login\Model;

include_once("Exceptions.php");

class Authentication {
  private $storage;
  private $db;
  private $lv;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Database $db, \Login\View\LoginView $lv) {
    $this->storage = $storage;
    $this->db = $db;
    $this->lv = $lv;
  }

  public function checkCorrectCredentials($credentials) : bool {
    $userCheck = $this->db->isAValidUser($credentials);

    if ($userCheck) {
      $this->storage->saveUser($credentials);
      $this->storage->setIsLoggedIn(true);
      return true;
    } else {
      throw new \WrongPasswordOrUsername("Wrong name or password");
      return false;
    }
  }
}