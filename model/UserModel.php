<?php

namespace Login\Model;

include_once("Exceptions.php");

class UserModel {
  private static $MIN_LENGHT = 2;
  private $username;
  private $password;

  public function __construct(string $username, string $password) {
    $this->username = $this->filtered($username);
    $this->password = $this->filtered($password);
    
    if (strlen($this->username) < self::$MIN_LENGHT) {
			throw new \TooShortNameException('<p>The name was to short</p>');
    }
    
    if (strlen($this->password) < self::$MIN_LENGHT) {
			throw new \TooShortPasswordException('The password was to short');
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