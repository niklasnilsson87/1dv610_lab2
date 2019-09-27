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
    $this->username = new \Login\Model\FilterUsername($this->filtered($username));
    $this->password = new \Login\Model\FilterPassword($this->filtered($password));
    $this->keep = $keep;
  }

  public function setName(UserModel $newName)
  {
    $this->username = $newName;
  }

  public function getName()
  {
    return $this->username->getName();
  }

  public function setPassword($password)
  {
    $this->password = $password;
  }

  public function getPassword()
  {
    return $this->password->getPassword();
  }

  public function filtered(string $rawString): string
  {
    return trim(htmlentities($rawString));
  }

  public function getKeepLoggedIn()
  {
    return $this->keep;
  }
}
