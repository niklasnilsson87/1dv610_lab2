<?php

require_once('view/LoginView.php');
require_once('model/Database.php');
require_once('model/UserStorage.php');
require_once('model/UserModel.php');
require_once('model/Authentication.php');
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
    $this->loginView = new \Login\View\LoginView($this->storage);
    $this->layoutView = new \Login\View\LayoutView();
    $this->auth = new \Login\Model\Authentication($this->storage, $this->db, $this->loginView);
    $this->loginController = new \Login\Controller\LoginController($this->storage, $this->auth, $this->loginView);

  }

  public function run() {
  // Check if user is logged in
  if ($this->storage->hasStoredUser()) {
    $this->user = $this->storage->loadUser();
    $this->storage->setIsLoggedIn(true);
  }

  if ($this->loginView->userWantsToLogout()) {
      $this->loginView->setMessage('Bye bye!');
			$this->storage->setIsLoggedIn(false);
			$_SESSION = array();
			session_destroy();
  }

  if ($this->loginView->userWantsToLogin()) {

    try {

      $this->loginController->tryToLogin();
      $this->storage->saveUser($this->loginView->getRequestUser());
      $this->loginView->setMessage('Welcome');

    } catch (\Exception $e) {

      $this->loginView->setMessage($e->getMessage());

    }

  }
  
  return $this->layoutView->render($this->storage->getIsLoggedIn(), $this->loginView, $this->date);
  }
}