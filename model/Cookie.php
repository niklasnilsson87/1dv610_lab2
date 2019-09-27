<?php

namespace Login\Model;

class Cookie
{

  private static $COOKIE_USER = 'LoginView::CookieName';
  private static $COOKIE_PASSWORD = 'LoginView::CookiePassword';

  public function hasCookie($user, $pwd): bool
  {
    return isset($_COOKIE[$user]) && isset($_COOKIE[$pwd]);
  }

  public function getUserByCookie($user, $pwd): \Login\Model\UserModel
  {
    $name = $_COOKIE[$user];
    $password = $_COOKIE[$pwd];
    return new \Login\Model\UserModel($name, $password, true);
  }

  public function removeCookie(): void
  {
    setcookie(self::$COOKIE_USER, '', time() - 3000);
    setcookie(self::$COOKIE_PASSWORD, '', time() - 3000);
  }

  public function saveCookie($credentials): void
  {
    if ($credentials->getKeepLoggedIn()) {
      $name = $credentials->getName();
      $password = $credentials->getPassword();
      $secret = $this->encodePassword($password);

      setcookie(self::$COOKIE_USER, $name, time() + 2000, "", "", false, true);

      setcookie(self::$COOKIE_PASSWORD, $secret, time() + 2000, "", "", false, true);
    }
  }

  public function encodePassword($password)
  {
    return base64_encode($password);
  }

  public function decodePassword($password)
  {
    return base64_decode($password);
  }
}
