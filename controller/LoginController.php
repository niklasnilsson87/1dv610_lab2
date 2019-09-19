<?php

namespace Login\Controller;

class LoginController {
  private $auth;
  public $loginView;
  private $storage;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Authentication $auth, \Login\View\LoginView $lv) {
    $this->storage = $storage;
    $this->auth = $auth;
    $this->loginView = $lv;
  }

  public function tryToLogin() {
    if ($this->loginView->userWantsToLogin()) {
      $credentials = $this->loginView->getRequestUser();
      $this->auth->checkCorrectCredentials($credentials);
  }
}

  public function setUserStorage() {
    $this->storage->saveUser();
  }
}