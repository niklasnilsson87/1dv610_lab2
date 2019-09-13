<?php

//INCLUDE THE FILES NEEDED...
require_once('model/DatabaseConfig.php');
require_once('model/LoginModel.php');
require_once('view/DateTimeView.php');
require_once('model/Database.php');
require_once('view/LayoutView.php');
require_once('view/LoginView.php');
require_once('controller/LoginController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$db = new \Login\Model\Database();
$loginModel = new \Login\Model\LoginModel($db);
$v = new \Login\View\LoginView();
$dtv = new \Login\View\DateTimeView();
$lv = new \Login\View\LayoutView();
$loginController = new \Login\Controller\LoginController($loginModel, $v);

$lv->render(false, $v, $dtv);

