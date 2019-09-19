<?php

namespace Login\Model;

include_once("Exceptions.php");

class Authentication {
  private $username;
  private $password;

  public function __construct(string $username, string $password) {
    $this->username = new Username($username);
    $this->password = new Password($password);
    
    if (empty($this->username)) {
			throw new \UsernameEmpty('Username is missing');
    }
    
    if (empty($this->password)) {
			throw new \PasswordEmpty('Password is missing');
		}
  }

  public function setName(UserModel $newName) {
		$this->username = $newName->getName();
  }
  
	public function getName() {
		return $this->username;
  }
  
  public function setPassword(UserModel $password) {
		$this->password = $password->getPassword();
  }

  public function getPassword() {
		return $this->password;
	}

  public function filtered(string $rawString) : string {
    return trim(htmlentities($rawString));
  }
}