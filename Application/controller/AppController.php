<?php

use Application\View\RunView;

include_once('Application/view/LayoutView.php');
include_once('Application/view/DateTimeView.php');
include_once('Application/view/RunningView.php');
include_once('Application/view/RunView.php');
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

  public function __construct($login)
  {
    $this->login = $login;
    $this->layoutView = new \Application\View\LayoutView();
    $this->dateView = new \Application\View\DateTimeView();
  }

  public function startApp()
  {
    $view = $this->login->getMainController()->startLogin();
    $isLoggedIn = $this->login->getMainController()->isAuthenticated();
    $loggedInUser = $this->login->getMainController()->getUsername();

    if ($isLoggedIn) {
      $this->runningView = new \Application\View\RunningView();
      $rs = new \Application\Model\RunStorage($loggedInUser);
      $runView = new \Application\View\RunView($rs->getRuns());
      $this->runController = new \Application\Controller\RunController($this->runningView, $rs);

      $newRunAdded = $this->runController->TryToAddRun($loggedInUser);
      if ($newRunAdded) {
        $runView = $this->createNewView($rs, $loggedInUser);
      }

      $runDeleted = $this->runController->tryToDeleteRun($runView);
      if ($runDeleted) {
        $runView = $this->createNewView($rs, $loggedInUser);
      }

      return $this->layoutView->render($isLoggedIn, $view, $this->dateView, $this->runningView, $runView);
    }

    return $this->layoutView->render($isLoggedIn, $view, $this->dateView);
  }

  private function createNewView($rs, $user)
  {
    $rs->updateRuns($user);
    return new \Application\View\RunView($rs->getRuns());
  }
}
