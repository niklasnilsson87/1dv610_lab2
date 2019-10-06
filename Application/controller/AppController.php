<?php

include_once('Application/view/LayoutView.php');
include_once('Application/view/DateTimeView.php');
include_once('Application/view/RunningView.php');
include_once('Application/controller/RunController.php');
include_once('Application/model/Run.php');

class AppController
{

  private $login;

  private $layoutView;
  private $dateView;
  private $runningView;
  private $runController;

  public function __construct($login)
  {
    $this->login = $login;
    $this->layoutView = new \Application\View\LayoutView();
    $this->dateView = new \Application\View\DateTimeView();
    $this->runningView = new \Application\View\RunningView();
    $this->runController = new \Application\Controller\RunController($this->runningView);
  }

  public function startApp()
  {
    $view = $this->login->getMainController()->startLogin();
    $isLoggedIn = $this->login->getMainController()->isAuthenticated();
    $this->runController->TryToSubmitRun();
    return $this->layoutView->render($isLoggedIn, $view, $this->runningView, $this->dateView);
  }
}
