<?php

namespace Login\Model;

class SessionState
{
	private static $SESSION_KEY =  __CLASS__ .  "::UserName";
	private static $SESSION_LOGGED_IN =  __CLASS__ .  "::IsLoggedIn";
	private static $SESSION_REGISTER_USER_MESSAGE = __CLASS__ . "::RegisterUser";

	public function hasStoredUser(): bool
	{
		return isset($_SESSION[self::$SESSION_KEY]);
	}

	public function loadUser(): string
	{
		if ($this->hasStoredUser()) {
			return $_SESSION[self::$SESSION_KEY];
		} else {
			return new \Exception('No user exist');
		}
	}

	public function saveUser(string $toBeSaved): void
	{
		$_SESSION[self::$SESSION_KEY] = $toBeSaved;
	}

	public function loadRegisterUser(): string
	{
		return $_SESSION[self::$SESSION_KEY];
	}

	public function saveRegisterMessage(string $toBeSaved): void
	{
		$_SESSION[self::$SESSION_REGISTER_USER_MESSAGE] = $toBeSaved;
	}

	public function isSavedMessage(): bool
	{
		return isset($_SESSION[self::$SESSION_REGISTER_USER_MESSAGE]);
	}

	public function getRegisterMessage(): string
	{
		return $_SESSION[self::$SESSION_REGISTER_USER_MESSAGE];
	}

	public function setIsLoggedIn(bool $toBeSaved): void
	{
		$_SESSION[self::$SESSION_LOGGED_IN] = $toBeSaved;
	}

	public function getIsLoggedIn(): bool
	{
		return isset($_SESSION[self::$SESSION_LOGGED_IN]);
	}

	public function unsetSession(): void
	{
		unset($_SESSION[self::$SESSION_KEY]);
		unset($_SESSION[self::$SESSION_LOGGED_IN]);
		unset($_SESSION[self::$SESSION_REGISTER_USER_MESSAGE]);
	}
}
