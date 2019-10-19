<?php

namespace Login\Model;

include_once("Exceptions.php");

class Authentication
{
  private $session;
  private $db;

  public function __construct(\Login\Model\SessionState $session)
  {
    $this->session = $session;
    $this->db =  new \Login\Model\Database();
  }

  public function tryToSaveUser(\Login\Model\UserModel $credentials): void
  {
    $userCheck = $this->db->isAValidUser($credentials);

    if ($userCheck) {
      $this->session->saveUser($credentials->getName());
      $this->session->setIsLoggedIn(true);
    } else {
      throw new \WrongPasswordOrUsername;
    }
  }

  public function doesUserExist(\Login\Model\RegistrationUser $credentials): bool
  {
    $name = $credentials->getName();
    if ($this->db->userExist($name)) {
      throw new \UserAlreadyExist;
    } else {
      return false;
    }
  }

  public function register(\Login\Model\RegistrationUser $credentials): void
  {
    $this->db->registerUser($credentials);
    $this->session->saveUser($credentials->getName());
  }
}
