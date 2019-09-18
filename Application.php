<?php

require_once('view/LoginView.php');
require_once('model/DatabaseConfig.php');
require_once('model/Database.php');
require_once('model/UserStorage.php');
require_once('model/UserModel.php');
require_once('controller/LoginController.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/Exceptions.php');


class Application {
  private $db;
  private $date;
  private $loginView;
  private $layoutView;
  private $loginController;
  
  private $storage;
	private $user;

  public function __construct() {
    $this->storage = new \Login\Model\UserStorage();
    
    $this->db = new \Login\Model\Database();
    $this->date = new \Login\View\DateTimeView();
    $this->loginView = new \Login\View\LoginView();
    $this->layoutView = new \Login\View\LayoutView();
    $this->loginController = new \Login\Controller\LoginController($this->storage, $this->db, $this->loginView);


  }

  public function run() {
  // Check if user is logged in
  $isLoggedIn = false;
  if ($this->storage->hasStoredUser()) {
    $this->user = $this->storage->loadUser();
    $isLoggedIn = true;
  }

  if ($this->loginView->userWantsToLogin()) {
    try {
      $isLoggedIn = $this->loginController->tryToLogin();
      $this->storage->saveUser($this->loginView->getRequestUser());
    } catch (\Exception $e) {
      $this->loginView->setMessage($e->getMessage());
    }
  }

  
  
  
  
  
  return $this->layoutView->render($isLoggedIn, $this->loginView, $this->date);
  }
}