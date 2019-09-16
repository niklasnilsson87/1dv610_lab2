<?php

namespace Login\Model;

class LoginModel {
  private $name;
  private $password;

  public function __construct() {
    $this->db = $db;
    $this->loginView = $loginView;
  }

  public function isUserSet() : bool {
    $name = $this->loginView->getRequestUserName();
    return empty(trim($name)) ? false : true;
  }

  public function isPasswordSet() : bool {
    $password = $this->loginView->getRequestPassword();
    return empty(trim($password)) ? false : true;
  }
}