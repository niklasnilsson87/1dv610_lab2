<?php

//INCLUDE THE FILES NEEDED...
session_start();
require_once('Application.php');
require_once('view/ErrorPage.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
// error_reporting(E_ALL);
// ini_set('display_errors', 'On');

$app = new Application();

try {
  $app->startApp();
} catch (\Exception $e) {
  ErrorPage::echoError($e->getMessage());
}
