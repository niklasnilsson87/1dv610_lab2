<?php

namespace Login\View;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $msg = '';


	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn = false) {
		
		if ($this->userWantsToLogOut()) {
			$this->setMessage('Bye bye!');
		}
		
		if ($isLoggedIn) {
			$this->setMessage('Welcome');
			$response = $this->generateLogoutButtonHTML(self::$msg);
		} else {
			$response = $this->generateLoginFormHTML(self::$msg);
		}


		return $response;
	}

	public function setMessage($msg) {
		self::$msg = $msg;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getPostUser() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUser() : \Login\Model\UserModel {
		//RETURN REQUEST VARIABLE: USERNAME
		$name = $_POST[self::$name];
		$pwd = $_POST[self::$password];
		return new \Login\Model\UserModel($name, $pwd);
	}

	private function getPostUser() {
		if($this->userWantsToLogin()) {
			return $_POST[self::$name];
		}
		return '';
	}

	public function userWantsToLogin() : bool {
		return $_SERVER['REQUEST_METHOD'] === 'POST' &&
		 isset($_POST[self::$login]) ? true : false;
		}
	
	private function userWantsToLogOut() {
		return isset($_POST[self::$logout]);
	}
}