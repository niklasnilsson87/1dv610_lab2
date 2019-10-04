<?php

namespace Login\View;

class Message
{
  const BYE = 'Bye bye!';
  const WELCOME_COOKIE = 'Welcome back with cookie';
  const USERNAME_EMPTY = 'Username is missing';
  const PASSWORD_EMPTY = 'Password is missing';
  const WRONG_PWD_OR_NAME = 'Wrong name or password';
  const REMEMBER_WELCOME = 'Welcome and you will be remembered';
  const WELCOME = 'Welcome';
  const USER_FEW_CHAR = 'Username has too few characters, at least 3 characters.';
  const PWD_FEW_CHAR = 'Password has too few characters, at least 6 characters.';
  const EMPTY_REG_FIELDS = 'Username has too few characters, at least 3 characters. <br> Password has too few characters, at least 6 characters.';
  const PASSWORD_DONT_MATCH = 'Passwords do not match.';
  const USER_EXIST = 'User exists, pick another username.';
  const CONTAINS_HTML = 'Username contains invalid characters.';
  const REGISTER_SUCCESS = 'Registered new user.';
  const WRONG_COOKIE = 'Wrong information in cookies';
}
