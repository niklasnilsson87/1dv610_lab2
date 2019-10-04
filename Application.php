<?php

use Login\Controller\LoginController;

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('model/Database.php');
require_once('model/UserStorage.php');
require_once('model/Cookie.php');
require_once('model/UserModel.php');
require_once('model/Authentication.php');
require_once('model/FilterUsername.php');
require_once('model/FilterPassword.php');
require_once('model/RegistrationUser.php');
require_once('controller/LoginController.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('model/Exceptions.php');
require_once('view/Message.php');


class Application
{
  private $date;
  private $loginView;
  private $registerView;
  private $layoutView;
  private $loginController;

  private $storage;
  private $cookieUser;
  private $cookiePassword;
  private $cookie;

  public function __construct()
  {
    $this->storage = new \Login\Model\UserStorage();
    $this->cookie = new \Login\Model\Cookie();

    $this->date = new \Login\View\DateTimeView();
    $this->loginView = new \Login\View\LoginView($this->storage);
    $this->layoutView = new \Login\View\LayoutView();
    $this->registerView = new \Login\View\RegisterView();
    $this->auth = new \Login\Model\Authentication($this->storage, $this->cookie);
    $this->loginController = new \Login\Controller\LoginController($this->storage, $this->auth, $this->loginView, $this->registerView, $this->cookie);

    $this->cookieUser = $this->loginView->getCookieName();
    $this->cookiePassword = $this->loginView->getCookiePassword();
  }

  public function startApp()
  {
    $this->loginController->tryToLoginByCookie($this->cookieUser, $this->cookiePassword);
    $this->loginController->checkStorageForUser();

    if ($this->storage->isSavedMessage()) {
      $this->loginView->setMessage($this->storage->getRegisterMessage());
      $this->loginView->setPostUser($this->storage->loadRegisterUser());
      $this->storage->destroySession();
    }

    $this->loginController->checkIfUserWantsToLogout();
    $this->loginController->checkIfUserWantsToLogin();

    $isLoggedIn = $this->storage->getIsLoggedIn();

    if ($this->layoutView->userWantsToRegister()) {
      $this->loginController->tryToRegister();
      return $this->layoutView->render($isLoggedIn, $this->registerView, $this->date);
    }
    return $this->layoutView->render($isLoggedIn, $this->loginView, $this->date);
  }
}
