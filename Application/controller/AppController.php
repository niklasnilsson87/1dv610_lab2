<?php

include_once('Application/view/LayoutView.php');
include_once('Application/view/DateTimeView.php');
include_once('Application/view/RunningView.php');

class AppController
{

  private $login;

  private $layoutView;
  private $dateView;
  private $runningView;

  public function __construct($login)
  {
    $this->login = $login;
    $this->layoutView = new \Application\View\LayoutView();
    $this->dateView = new \Application\View\DateTimeView();
    $this->runningView = new \Application\View\RunningView();
  }

  public function startApp()
  {
    $view = $this->login->getMainController()->startLogin();
    $isLoggedIn = $this->login->getMainController()->isAuthenticated();
    return $this->layoutView->render($isLoggedIn, $view, $this->runningView, $this->dateView);
  }
}
