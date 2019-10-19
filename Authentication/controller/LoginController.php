<?php

namespace Login\Controller;

include_once("Authentication/model/Exceptions.php");

class LoginController
{
  private $auth;
  public $loginView;
  private $session;

  public function __construct(\Login\Model\SessionState $session, \Login\Model\Authentication $auth, \Login\View\LoginView $lv)
  {
    $this->session = $session;
    $this->auth = $auth;
    $this->loginView = $lv;
  }

  public function tryToLoginByCookie(): void
  {
    try {
      if ($this->loginView->hasCookie() && !$this->session->getIsLoggedIn()) {
        $this->loginByCookie();
        $this->loginView->setMessage(\Login\View\Message::WELCOME_COOKIE);
      }
    } catch (\Exception $e) {
      $this->loginView->setMessage(\Login\View\Message::WRONG_COOKIE);
      $this->loginView->removeCookie();
      $this->session->unsetSession();
    }
  }

  public function checkSessionForUser(): void
  {
    if ($this->session->hasStoredUser()) {
      $this->session->setIsLoggedIn(true);
    }
  }

  public function checkIfUserWantsToLogout(): void
  {
    if ($this->loginView->userWantsToLogout()) {
      $this->loginView->setMessage(\Login\View\Message::BYE);
      $this->session->setIsLoggedIn(false);
      $this->loginView->removeCookie();
      $this->session->unsetSession();
    }
  }

  public function checkIfUserWantsToLogin(): void
  {
    if ($this->loginView->userWantsToLogin()) {

      try {
        $this->tryToLogin();
        $user = $this->loginView->getRequestUser();
        $this->session->saveUser($user->getName());
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

  public function tryToLogin(): void
  {
    if ($this->loginView->userWantsToLogin()) {
      $credentials = $this->loginView->getRequestUser();
      $this->auth->tryToSaveUser($credentials);
      $this->loginView->saveCookie($credentials);
    }
  }

  public function loginByCookie(): void
  {
    $credentialsByCookie = $this->loginView->getUserByCookie();
    $decodedPassword = $this->loginView->decodeCookiePassword($credentialsByCookie->getPassword());
    $newPwd = $credentialsByCookie->createNewPassword($decodedPassword);
    $credentialsByCookie->setPassword($newPwd);
    $this->auth->tryToSaveUser($credentialsByCookie);
    $this->loginView->saveCookie($credentialsByCookie);
  }

  private function checkIfKeepLogin(): void
  {
    if ($this->loginView->getKeepLoggedIn()) {
      $this->loginView->setMessage(\Login\View\Message::REMEMBER_WELCOME);
    } else {
      $this->loginView->setMessage(\Login\View\Message::WELCOME);
    }
  }
}
