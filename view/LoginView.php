<?php

namespace Login\View;

class LoginView
{
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $msg = '';

	private $storage;

	public function __construct(\Login\Model\UserStorage $storage)
	{
		$this->storage = $storage;
	}
	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn): string
	{

		if ($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML(self::$msg);
		} else {
			$response = $this->generateLoginFormHTML(self::$msg);
		}

		return $response;
	}

	public function setMessage($msg): void
	{
		self::$msg = $msg;
	}

	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
	private function generateLogoutButtonHTML($message): string
	{
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
	private function generateLoginFormHTML($message): string
	{
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getPostUsername() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	public function getRequestUser(): \Login\Model\UserModel
	{
		$name = $_POST[self::$name];
		$pwd = $_POST[self::$password];
		$keep = $this->getKeepLoggedIn();
		return new \Login\Model\UserModel($name, $pwd, $keep);
	}

	public function getKeepLoggedIn(): bool
	{
		return isset($_POST[self::$keep]);
	}

	public function getCookieName()
	{
		return self::$cookieName;
	}

	public function getCookiePassword()
	{
		return self::$cookiePassword;
	}

	private function getPostUsername(): string
	{
		if ($this->userWantsToLogin()) {
			return $_POST[self::$name];
		}
		return '';
	}

	public function userWantsToLogin(): bool
	{
		return isset($_POST[self::$login]) &&
			!$this->storage->hasStoredUser();
	}

	public function userWantsToLogOut(): bool
	{
		return isset($_POST[self::$logout]) &&
			$this->storage->hasStoredUser();
	}
}
