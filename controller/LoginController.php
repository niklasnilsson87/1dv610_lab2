<?php

namespace Login\Controller;

class LoginController
{
  private $auth;
  public $loginView;
  private $storage;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Authentication $auth, \Login\View\LoginView $lv)
  {
    $this->storage = $storage;
    $this->auth = $auth;
    $this->loginView = $lv;
  }

  public function tryToLogin(): void
  {
    if ($this->loginView->userWantsToLogin()) {
      $credentials = $this->loginView->getRequestUser();
      $this->auth->tryToSaveUser($credentials);
    }
  }

  public function loginByCookie(): void
  {
    $credentialsByCookie = $this->auth->getUserByCookie();
    $this->auth->tryToSaveUser($credentialsByCookie);
  }

  public function tryToLoginByCookie(): void
  {
    try {
      if ($this->auth->hasCookie() && !$this->storage->getIsLoggedIn()) {
        $this->loginByCookie();
        $this->loginView->setMessage('Welcome back with cookie');
      }
    } catch (\Exception $e) {
      $this->loginView->setMessage('Wrong information in cookies');
      $this->auth->removeCookie();
      $this->storage->destroySession();
    }
  }

  public function checkStorageForUser(): void
  {
    if ($this->storage->hasStoredUser()) {
      $this->storage->setIsLoggedIn(true);
    }
  }

  public function checkIfUserWantsToLogout(): void
  {
    if ($this->loginView->userWantsToLogout()) {
      $this->loginView->setMessage('Bye bye!');
      $this->storage->setIsLoggedIn(false);
      $this->auth->removeCookie();
      $this->storage->destroySession();
    }
  }

  public function checkIfUserWantsToLogin(): void
  {
    if ($this->loginView->userWantsToLogin()) {

      try {

        $this->tryToLogin();
        $this->storage->saveUser($this->loginView->getRequestUser());
        if ($this->loginView->getKeepLoggedIn()) {
          $this->loginView->setMessage('Welcome and you will be remembered');
        } else {
          $this->loginView->setMessage('Welcome');
        }
      } catch (\Exception $e) {

        $this->loginView->setMessage($e->getMessage());
      }
    }
  }

  public function checkIfKeepLogin(): void
  {
    if ($this->loginView->getKeepLoggedIn()) {
      $this->loginView->setMessage('Welcome and you will be remembered');
    } else {
      $this->loginView->setMessage('Welcome');
    }
  }
}
