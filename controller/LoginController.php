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
        $pwd = $this->loginView->getRequestUser()->getPassword();
        $dbCheck = $this->db->isAValidUser($username, $pwd);
        return $dbCheck;
      } catch (\Exception $e) {
        $this->loginView->setMessage($e->getMessage());
      }
    }
  }
}