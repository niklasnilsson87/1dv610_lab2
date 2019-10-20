<?php

session_start();
include_once('controller/MainController.php');
include_once('view/ErrorPage.php');

class Authentication
{

  private $login;

  public function __construct()
  {
    $this->login = new \Login\Controller\MainController();
  }

  public function getMainController()
  {
    try {
      return $this->login;
    } catch (\Exception $e) {
      ErrorPage::echoError($e->getMessage());
    }
  }
}
