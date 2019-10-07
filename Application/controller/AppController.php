<?php

include_once('Application/view/LayoutView.php');
include_once('Application/view/DateTimeView.php');
include_once('Application/view/RunningView.php');
include_once('Application/controller/RunController.php');
include_once('Application/model/Run.php');
include_once('Application/model/RunStorage.php');
include_once('Application/model/Database.php');


class AppController
{

  private $login;

  private $layoutView;
  private $dateView;
  private $runningView;
  private $runController;
  private $runStorage;
  private $loggedInUser;

  public function __construct($login)
  {
    $this->login = $login;
    $this->loggedInUser = $this->login->getMainController()->getUsername();
    $this->runStorage = new \Application\Model\RunStorage($this->loggedInUser);
    $this->layoutView = new \Application\View\LayoutView();
    $this->dateView = new \Application\View\DateTimeView();
    $this->runningView = new \Application\View\RunningView($this->runStorage);
    $this->runController = new \Application\Controller\RunController($this->runningView, $this->runStorage);
  }

  public function startApp()
  {
    $view = $this->login->getMainController()->startLogin();
    $isLoggedIn = $this->login->getMainController()->isAuthenticated();
    $this->runController->TryToSubmitRun($this->loggedInUser);
    // $this->runStorage->updateRuns($this->loggedInUser);
    return $this->layoutView->render($isLoggedIn, $view, $this->runningView, $this->dateView);
  }
}
