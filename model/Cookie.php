<?php

namespace Login\Model;

class Cookie
{

  public function hasCookie(): bool
  {
    return isset($_COOKIE['username']) && isset($_COOKIE['password']);
  }

  public function getUserByCookie(): \Login\Model\UserModel
  {
    $name = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    return new \Login\Model\UserModel($name, $password, true);
  }

  public function removeCookie(): void
  {
    setcookie('username', '', time() - 3000);
    setcookie('password', '', time() - 3000);
  }

  public function saveCookie($credentials): void
  {
    if ($credentials->getKeepLoggedIn()) {
      $name = $credentials->getName();
      $password = $credentials->getPassword();

      setcookie('username', $name, time() + 2000, "", "", false, true);

      setcookie('password', $password, time() + 2000, "", "", false, true);
    }
  }
}
