<?php

namespace Login\Model;

class UserStorage {

	private static $SESSION_KEY =  __CLASS__ .  "::Username";
	private static $SESSION_LOGGED_IN =  __CLASS__ .  "::IsLoggedIn";
	
	
	public function hasStoredUser() {
		if (isset($_SESSION[self::$SESSION_KEY])) {
			return true;
		} else {
			false;
		}
	}

	public function loadUser() {
		if ($this->hasStoredUser()) {
			return $_SESSION[self::$SESSION_KEY];
		} else {
			return new \Exception('No user exist');
		}
	}

	public function saveUser(UserModel $toBeSaved) {
		$_SESSION[self::$SESSION_KEY] = $toBeSaved->getName();
	}

	public function setIsLoggedIn(bool $toBeSaved) {
		$_SESSION[self::$SESSION_LOGGED_IN] = $toBeSaved;
	}

	public function getIsLoggedIn() {
		return $_SESSION[self::$SESSION_LOGGED_IN];
	}
}