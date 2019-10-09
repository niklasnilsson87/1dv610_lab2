<?php

require_once('Authentication/view/LoginView.php');
require_once('Authentication/view/RegisterView.php');
require_once('Authentication/view/Message.php');

require_once('Authentication/model/Database.php');
require_once('Authentication/model/UserStorage.php');
require_once('Authentication/model/UserModel.php');
require_once('Authentication/model/Authentication.php');
require_once('Authentication/model/FilterUsername.php');
require_once('Authentication/model/FilterPassword.php');
require_once('Authentication/model/RegistrationUser.php');
require_once('Authentication/model/Exceptions.php');

require_once('LoginController.php');
require_once('RegisterController.php');


class MainController
{
  private $loginView;
  private $registerView;

  private $loginController;
  private $registerController;

  private $storage;

  public function __construct()
  {
    $this->storage = new \Login\Model\UserStorage();
    $auth = new \Login\Model\Authentication($this->storage);

    $this->loginView = new \Login\View\LoginView($this->storage);
    $this->registerView = new \Login\View\RegisterView();

    $this->loginController = new \Login\Controller\LoginController($this->storage, $auth, $this->loginView);
    $this->registerController = new \Login\Controller\RegisterController($this->storage, $this->registerView, $auth);
  }

  public function startLogin()
  {
    $this->loginController->tryToLoginByCookie();
    $this->loginController->checkStorageForUser();
    $this->checkSavedMessage($this->storage, $this->loginView);


    $this->loginController->checkIfUserWantsToLogout();
    $this->loginController->checkIfUserWantsToLogin();

    if ($this->registerView->userWantsToRegister()) {
      $this->registerController->tryToRegister();
      return $this->registerView;
    }
    return $this->loginView;
  }

  private function checkSavedMessage($storage, $view)
  {
    if ($storage->isSavedMessage()) {
      $view->setMessage($this->storage->getRegisterMessage());
      $view->setPostUser($this->storage->loadRegisterUser());
      $storage->unsetSession();
    }
  }

  public function isAuthenticated()
  {
    return $this->storage->getIsLoggedIn();
  }

  public function getUsername()
  {
    return $this->storage->loadUser();
  }
}
