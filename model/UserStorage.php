<?php

namespace Login\Model;

session_start();

class UserStorage {

  private static $SESSION_USER =  __CLASS__ .  "::Username";
  private static $SESSION_KEY =  __CLASS__ .  "::Password";
  
	public function loadUser() {
		if (isset($_SESSION[self::$SESSION_KEY])) {
			return $_SESSION[self::$SESSION_KEY];
		} else {
			return new UserModel("default", "default");
		}
	}
	public function saveUser(UserName $user) {
		$_SESSION[self::$SESSION_USER] = $user;
		$_SESSION[self::$SESSION_KEY] = $toBeSaved;
	}
}