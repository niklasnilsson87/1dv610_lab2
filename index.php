<?php

//INCLUDE THE FILES NEEDED...
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
require_once('Application.php');
require_once('view/ErrorPage.php');
// var_dump($_SESSION);
//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$app = new Application();

// echo $_SERVER['HTTP_USER_AGENT'];

try {
  $app->run();
} catch (\Exception $e) {
  ErrorPage::echoError($e->getMessage());
}

// var_dump($_SESSION);
