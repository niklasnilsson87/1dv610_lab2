<?php

namespace Login\Model;

include_once('Exceptions.php');

class RegistrationUser
{
  private $username;
  private $password;
  private $passwordCheck;

  public function __construct($username, $password, $passwordCheck)
  {
    $this->username = new FilterUsername($username);
    $this->password = new FilterPassword($password);
    $this->passwordCheck = new FilterPassword($passwordCheck);
  }

  public function getUser()
  {
    return $this->username;
  }

  public function getUserPassword()
  {
    return $this->password;
  }
}
