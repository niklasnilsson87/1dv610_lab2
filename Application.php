<?php

require_once('view/LoginView.php');
require_once('model/DatabaseConfig.php');
require_once('model/Database.php');
require_once('model/UserModel.php');
require_once('controller/LoginController.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

class Application {
  private $db;
  private $date;
  // private $loginModel;
  private $loginView;
  private $layoutView;
  private $loginController;
  
  // private $storage;
	// private $user;

  public function __construct() {
    $this->db = new \Login\Model\Database();
    $this->date = new \Login\View\DateTimeView();
    $this->loginView = new \Login\View\LoginView();
    // $this->user = new \Login\Model\UserModel("hej");
    // $this->loginModel = new \Login\Model\LoginModel($this->db, $this->loginView);
    $this->layoutView = new \Login\View\LayoutView();
    $this->loginController = new \Login\Controller\LoginController($this->db, $this->loginView);

    // $this->storage = new \Model\UserStorage();
		// $this->user = $this->storage->loadUser();

  }

  public function run() {
  // Check if user is logged in
   // load Session
  //  if ($this->loginView->userWantsToLogin()) {
  //   $user = $this->loginView->getRequestUser()->getName();
  //   $pwd = $this->loginView->getRequestUser()->getPassword();
  //   echo $user . "<br>";
  //   echo $pwd;
  //  }
    $this->loginController->tryToLogin();
    return $this->layoutView->render(false, $this->loginView, $this->date);
      //login = true
          //show logged in view
      //login = false
          // show loginView
  }
  

}