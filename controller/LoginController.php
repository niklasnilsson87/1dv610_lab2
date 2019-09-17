<?php

namespace Login\Controller;

class LoginController {
  private $db;
  public $loginView;

  public function __construct(\Login\Model\Database $db, \Login\View\LoginView $lv) {
    $this->db = $db;
    $this->loginView = $lv;
  }

  public function tryToLogin() {
    if ($this->loginView->userWantsToLogin()) {
      try {
      $username = $this->loginView->getRequestUser()->getName();
      var_dump($username);
      $pwd = $this->loginView->getRequestUser()->getPassword();
      var_dump($pwd);
      $dbCheck = $this->db->getUser($username, $pwd);
      var_dump($dbCheck);
      return $dbCheck;
    } catch (\Exception $e) {
      $this->loginView->setMessage($e->getMessage());
    }
    }
    // return $this->loginView->userWantsToLogin();
  }

}