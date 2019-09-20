<?php

namespace Login\Model;

include_once("Exceptions.php");

class UserModel
{
  private $username;
  private $password;
  private $keep;

  public function __construct(string $username, string $password, bool $keep)
  {
    $this->username = $this->filtered($username);
    $this->password = $this->filtered($password);
    $this->keep = $keep;

    if (empty($this->username)) {
      throw new \UsernameEmpty('Username is missing');
    }

    if (empty($this->password)) {
      throw new \PasswordEmpty('Password is missing');
    }
  }

  public function setName(UserModel $newName)
  {
    $this->username = $newName->getName();
  }

  public function getName()
  {
    return $this->username;
  }

  public function setPassword(UserModel $password)
  {
    $this->password = $password->getPassword();
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function filtered(string $rawString): string
  {
    return trim(htmlentities($rawString));
  }
}
