<?php

namespace Login\Controller;

use Login\Model\FilterPassword;

include_once("Authentication/model/Exceptions.php");

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
    $decodedPassword = $this->cookie->decodePassword($credentialsByCookie->getPassword());
    $newPwd = new FilterPassword($decodedPassword);
    $credentialsByCookie->setPassword($newPwd);
    $this->auth->tryToSaveUser($credentialsByCookie);
  }

  public function tryToLoginByCookie($user, $pwd): void
  {
    try {
      if ($this->cookie->hasCookie($user, $pwd) && !$this->storage->getIsLoggedIn()) {
        $this->loginByCookie($user, $pwd);
        $this->loginView->setMessage(\Login\View\Message::WELCOME_COOKIE);
      }
    } catch (\Exception $e) {
      $this->loginView->setMessage(\Login\View\Message::WRONG_COOKIE);
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
      $this->loginView->setMessage(\Login\View\Message::BYE);
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
        $this->loginView->setMessage(\Login\View\Message::USERNAME_EMPTY);
      } catch (\PasswordEmpty $e) {
        $this->loginView->setMessage(\Login\View\Message::PASSWORD_EMPTY);
      } catch (\WrongPasswordOrUsername $e) {
        $this->loginView->setMessage(\Login\View\Message::WRONG_PWD_OR_NAME);
      }
    }
  }

  private function checkIfKeepLogin(): void
  {
    if ($this->loginView->getKeepLoggedIn()) {
      $this->loginView->setMessage(\Login\View\Message::REMEMBER_WELCOME);
    } else {
      $this->loginView->setMessage(\Login\View\Message::WELCOME);
    }
  }

  public function tryToRegister()
  {
    if ($this->registerView->userClicksRegister()) {
      try {
        $regCredentials = $this->registerView->checkUser();
        $this->registerUser($regCredentials);
      } catch (\UsernameEmpty $e) {
        $this->registerView->setMessage(\Login\View\Message::USER_FEW_CHAR);
      } catch (\PasswordEmpty $e) {
        $this->registerView->setMessage(\Login\View\Message::PWD_FEW_CHAR);
      } catch (\UsernameAndPasswordEmpty $e) {
        $this->registerView->setMessage(\Login\View\Message::EMPTY_REG_FIELDS);
      } catch (\PasswordDoesNotMatch $e) {
        $this->registerView->setMessage(\Login\View\Message::PASSWORD_DONT_MATCH);
      } catch (\UserAlreadyExist $e) {
        $this->registerView->setMessage(\Login\View\Message::USER_EXIST);
      } catch (\ContainsHTML $e) {
        $this->registerView->setMessage(\Login\View\Message::CONTAINS_HTML);
      }
    }
  }

  public function registerUser($credentials)
  {
    if (!$this->auth->doesUserExist($credentials)) {
      $this->auth->register($credentials);
      $this->storage->saveRegisterMessage(\Login\View\Message::REGISTER_SUCCESS);
      header("Location: ?");
    }
  }
}
