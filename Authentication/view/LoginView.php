<?php

namespace Login\View;

include_once('IView.php');

class LoginView implements IView
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
	private $valueToInput = '';

	private $session;

	public function __construct(\Login\Model\SessionState $session)
	{
		$this->session = $session;
	}

	public function response(bool $isLoggedIn): string
	{
		$this->getPostUsername();
		if ($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML(self::$msg);
		} else {
			$response = $this->generateLoginFormHTML(self::$msg);
		}

		return $response;
	}

	private function generateLogoutButtonHTML(string $message): string
	{
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	private function generateLoginFormHTML(string $message): string
	{
		if ($this->session->isSavedMessage()) {
			$this->valueToInput = $this->session->loadRegisterUser();
		}
		return '
			<form method="post">
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->valueToInput . '" />

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

	public function getPostUsername(): void
	{
		if ($this->userWantsToLogin()) {
			$this->valueToInput = $_POST[self::$name];
		} else if (isset($this->valueToInput)) {
			$this->valueToInput;
		}
	}

	public function setMessage(string $msg): void
	{
		self::$msg = $msg;
	}

	public function setPostUser(string $user): void
	{
		$this->valueToInput = $user;
	}

	public function getKeepLoggedIn(): bool
	{
		return isset($_POST[self::$keep]);
	}

	public function userWantsToLogin(): bool
	{
		return isset($_POST[self::$login]) &&
			!$this->session->hasStoredUser();
	}

	public function userWantsToLogOut(): bool
	{
		return isset($_POST[self::$logout]) &&
			$this->session->hasStoredUser();
	}

	public function hasCookie(): bool
	{
		return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
	}

	public function getUserByCookie(): \Login\Model\UserModel
	{
		$name = $_COOKIE[self::$cookieName];
		$password = $_COOKIE[self::$cookiePassword];
		return new \Login\Model\UserModel($name, $password, true);
	}

	public function removeCookie(): void
	{
		setcookie(self::$cookieName, '', time() - 3000);
		setcookie(self::$cookiePassword, '', time() - 3000);
	}

	public function saveCookie(\Login\Model\UserModel $credentials): void
	{
		if ($credentials->getKeepLoggedIn()) {
			$name = $credentials->getName();
			$password = $credentials->getPassword();
			$secret = $this->encodeCookiePassword($password);

			setcookie(self::$cookieName, $name, time() + 2000, "", "", false, true);

			setcookie(self::$cookiePassword, $secret, time() + 2000, "", "", false, true);
		}
	}

	private function encodeCookiePassword(string $password): string
	{
		return base64_encode($password);
	}

	public function decodeCookiePassword(string $password): string
	{
		return base64_decode($password);
	}
}
