<?php

//INCLUDE THE FILES NEEDED...
session_start();
require_once('MainController.php');
require_once('view/ErrorPage.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
// error_reporting(E_ALL);
// ini_set('display_errors', 'On');


class Authentication
{

  private $login;

  public function __construct()
  {
    $this->login = new MainController();
  }

  public function getLogin()
  {
    try {
      return $this->login->startLogin();
    } catch (\Exception $e) {
      ErrorPage::echoError($e->getMessage());
    }
  }
}
