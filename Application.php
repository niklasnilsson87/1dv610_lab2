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


class Application
{
  private $db;
  private $date;
  public $loginView;
  private $layoutView;
  private $loginController;

  private $storage;
  private $user;

  public function __construct()
  {
    $this->storage = new \Login\Model\UserStorage();

    $this->db = new \Login\Model\Database();
    $this->date = new \Login\View\DateTimeView();
    $this->loginView = new \Login\View\LoginView($this->storage);
    $this->layoutView = new \Login\View\LayoutView();
    $this->auth = new \Login\Model\Authentication($this->storage, $this->db, $this->loginView);
    $this->loginController = new \Login\Controller\LoginController($this->storage, $this->auth, $this->loginView);
  }

  public function run()
  {
    try {
      if ($this->auth->hasCookie() && !$this->storage->getIsLoggedIn()) {
        $this->loginController->loginByCookie();
        $this->loginView->setMessage('Welcome back with cookie');
      }
    } catch (\Exception $e) {
      $this->loginView->setMessage('Wrong information in cookies');
      $this->auth->removeCookie();
      $this->storage->destroySession();
    }
    // Check if user is logged in by session
    if ($this->storage->hasStoredUser()) {
      $this->user = $this->storage->loadUser();
      $this->storage->setIsLoggedIn(true);
    }

    if ($this->loginView->userWantsToLogout()) {
      $this->loginView->setMessage('Bye bye!');
      $this->storage->setIsLoggedIn(false);
      $this->auth->removeCookie();
      $this->storage->destroySession();
    }

    if ($this->loginView->userWantsToLogin()) {

      try {

        $this->loginController->tryToLogin();
        $this->storage->saveUser($this->loginView->getRequestUser());
        if ($this->loginView->getKeepLoggedIn()) {
          $this->loginView->setMessage('Welcome and you will be remembered');
        } else {
          $this->loginView->setMessage('Welcome');
        }
      } catch (\Exception $e) {

        $this->loginView->setMessage($e->getMessage());
      }
    }

    return $this->layoutView->render($this->storage->getIsLoggedIn(), $this->loginView, $this->date);
  }
}
