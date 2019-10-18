<?php
include_once('Authentication/index.php');
include_once('Application/controller/AppController.php');

$auth = new Authentication();
$login = $auth->getMainController();

$app = new AppController($login);
$app->startApp();
