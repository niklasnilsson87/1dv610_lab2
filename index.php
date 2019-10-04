<?php
include_once('Authentication/index.php');
include_once('Application/controller/AppController.php');

$auth = new Authentication();

$app = new AppController($auth);
$app->startApp();
