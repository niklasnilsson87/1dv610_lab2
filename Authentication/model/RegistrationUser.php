<?php

namespace Login\Model;

include_once('Exceptions.php');

class RegistrationUser
{
  private $username;
  private $password;

  public function __construct($username, $password, $passwordCheck)
  {
    if (empty($username) && empty($password)) {
      throw new \UsernameAndPasswordEmpty;
    }

    if ($password != $passwordCheck) {
      throw new \PasswordDoesNotMatch;
    }

    if ($username != strip_tags($username)) {
      throw new \ContainsHTML;
    }

    $this->username = new \Login\Model\FilterUsername($username);
    $this->password = new \Login\Model\FilterPassword($password);
  }

  public function getName()
  {
    return $this->username->getName();
  }

  public function getUserPassword()
  {
    return $this->password;
  }
}
