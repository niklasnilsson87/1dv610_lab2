<?php

namespace Login\Controller;

class LoginController {
  private $db;
  public $loginView;
  private $storage;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Database $db, \Login\View\LoginView $lv) {
    $this->storage = $storage;
    $this->db = $db;
    $this->loginView = $lv;
  }

  public function tryToLogin() {
    if ($this->loginView->userWantsToLogin()) {
        $credentials = $this->loginView->getRequestUser();
        $dbCheckIfUserExist = $this->db->isAValidUser($credentials);
        return $dbCheckIfUserExist;
  }
}

  public function setUserStorage() {
    $this->storage->saveUser();
  }
}