<?php

namespace Login\Controller;

class RegisterController
{
  private $storage;
  private $registerView;
  private $auth;

  public function __construct($storage, $registerView, $auth)
  {
    $this->storage = $storage;
    $this->registerView = $registerView;
    $this->auth = $auth;
  }
  public function tryToRegister()
  {
    if ($this->registerView->userClicksRegister()) {
      try {
        $regCredentials = $this->registerView->getRegisterUser();
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
