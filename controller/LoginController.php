<?php

namespace Login\Controller;

include_once("model/Exceptions.php");

class LoginController
{
  private $auth;
  public $loginView;
  public $registerView;
  private $storage;
  private $cookie;

  public function __construct(\Login\Model\UserStorage $storage, \Login\Model\Authentication $auth, \Login\View\LoginView $lv, \Login\View\RegisterView $rv, \Login\Model\Cookie $cookie)
  {
    $this->storage = $storage;
    $this->auth = $auth;
    $this->loginView = $lv;
    $this->registerView = $rv;
    $this->cookie = $cookie;
  }

  public function tryToLogin(): void
  {
    if ($this->loginView->userWantsToLogin()) {
      $credentials = $this->loginView->getRequestUser();
      $this->auth->tryToSaveUser($credentials);
    }
  }

  public function loginByCookie($user, $pwd): void
  {

    $credentialsByCookie = $this->cookie->getUserByCookie($user, $pwd);
    $this->auth->tryToSaveUser($credentialsByCookie);
  }

  public function tryToLoginByCookie($user, $pwd): void
  {
    try {
      if ($this->cookie->hasCookie($user, $pwd) && !$this->storage->getIsLoggedIn()) {
        $this->loginByCookie($user, $pwd);
        $this->loginView->setMessage('Welcome back with cookie');
      }
    } catch (\Exception $e) {
      $this->loginView->setMessage('Wrong information in cookies');
      $this->cookie->removeCookie();
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
      $this->cookie->removeCookie();
      $this->storage->destroySession();
    }
  }

  public function checkIfUserWantsToLogin(): void
  {
    if ($this->loginView->userWantsToLogin()) {

      try {
        $this->tryToLogin();
        $this->storage->saveUser($this->loginView->getRequestUser());
        $this->checkIfKeepLogin();
      } catch (\UsernameEmpty $e) {
        $this->loginView->setMessage('Username is missing');
      } catch (\PasswordEmpty $e) {
        $this->loginView->setMessage('Password is missing');
      } catch (\WrongPasswordOrUsername $e) {
        $this->loginView->setMessage('Wrong name or password');
      }
    }
  }

  private function checkIfKeepLogin(): void
  {
    if ($this->loginView->getKeepLoggedIn()) {
      $this->loginView->setMessage('Welcome and you will be remembered');
    } else {
      $this->loginView->setMessage('Welcome');
    }
  }

  public function tryToRegister()
  {
    if ($this->registerView->userClicksRegister()) {
      try {
        $regCredentials = $this->registerView->checkUser();
        $this->auth->doesUserExist($regCredentials);
      } catch (\UsernameEmpty $e) {
        $this->registerView->setMessage('Username has too few characters, at least 3 characters.');
      } catch (\PasswordEmpty $e) {
        $this->registerView->setMessage('Password has too few characters, at least 6 characters.');
      } catch (\UsernameAndPasswordEmpty $e) {
        $this->registerView->setMessage('Username has too few characters, at least 3 characters. <br> Password has too few characters, at least 6 characters.');
      } catch (\PasswordDoesNotMatch $e) {
        $this->registerView->setMessage('Passwords do not match.');
      } catch (\UserAlreadyExist $e) {
        $this->registerView->setMessage('User exists, pick another username.');
      }
    }
  }
}
