<?php

namespace Login\View;

class RegisterView implements IView
{
  private static $message = 'RegisterView::Message';
  private static $username = 'RegisterView::UserName';
  private static $password = 'RegisterView::Password';
  private static $passwordRepeat = 'RegisterView::PasswordRepeat';
  private static $register = 'RegisterView::Register';
  private static $msg = '';

  public function response(bool $isLoggedIn): string
  {
    $response = $this->generateRegisterFormHTML(self::$msg);

    return $response;
  }

  public function generateRegisterFormHTML()
  {
    return '<h2>Register new user</h2>
    <form action="?register" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>Register a new user - Write username and password</legend>
          <p id="' . self::$message . '"> ' . self::$msg . ' </p>
          <label for="' . self::$username . '" >Username :</label>
          <input type="text" size="20" name="' . self::$username . '" id="' . self::$username . '" value="' . $this->isPostUsername() . '" />
          <br/>
          <label for="' . self::$password . '" >Password  :</label>
          <input type="password" size="20" name="' . self::$password . '" id="' . self::$password . '" value="" />
          <br/>
          <label for="' . self::$passwordRepeat . '" >Repeat password  :</label>
          <input type="password" size="20" name="' . self::$passwordRepeat . '" id="' . self::$passwordRepeat . '" value="" />
          <br/>
          <input id="submit" type="submit" name="' . self::$register . '"  value="Register" />
          <br/>
      </fieldset>
      ';
  }

  public function isPostUsername(): string
  {
    if ($this->userClicksRegister()) {
      return strip_tags($_POST[self::$username]);
    }
    return '';
  }

  public function setMessage(string $msg): void
  {
    self::$msg = $msg;
  }

  public function userClicksRegister(): bool
  {
    return isset($_POST[self::$register]);
  }

  public function getRegisterUser(): \Login\Model\RegistrationUser
  {
    if ($this->userClicksRegister()) {

      $name = $_POST[self::$username];
      $password = $_POST[self::$password];
      $passwordCheck = $_POST[self::$passwordRepeat];
      return new \Login\Model\RegistrationUser($name, $password, $passwordCheck);
    }
  }

  public function userWantsToRegister(): bool
  {
    return isset($_GET['register']);
  }
}
