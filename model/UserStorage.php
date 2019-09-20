<?php

namespace Login\Model;

class UserStorage
{

	private static $SESSION_KEY =  __CLASS__ .  "::Username";
	private static $SESSION_KEEP =  __CLASS__ .  "::KeepMeLoggedIn";
	private static $SESSION_LOGGED_IN =  __CLASS__ .  "::IsLoggedIn";


	public function hasStoredUser()
	{
		if (isset($_SESSION[self::$SESSION_KEY])) {
			return true;
		} else {
			false;
		}
	}

	public function loadUser()
	{
		if ($this->hasStoredUser()) {
			return $_SESSION[self::$SESSION_KEY];
		} else {
			return new \Exception('No user exist');
		}
	}

	public function saveUser(UserModel $toBeSaved)
	{
		$_SESSION[self::$SESSION_KEY] = $toBeSaved->getName();
		$_SESSION[self::$SESSION_KEEP] = $toBeSaved->getKeepLoggedIn();
	}

	public function setIsLoggedIn(bool $toBeSaved)
	{
		$_SESSION[self::$SESSION_LOGGED_IN] = $toBeSaved;
	}

	public function getIsLoggedIn()
	{
		return isset($_SESSION[self::$SESSION_LOGGED_IN]);
	}

	public function getKeep()
	{
		return self::$SESSION_LOGGED_KEEP;
	}
}
