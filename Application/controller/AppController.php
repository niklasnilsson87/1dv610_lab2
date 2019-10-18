<?php

include_once('Application/view/LayoutView.php');
include_once('Application/view/DateTimeView.php');
include_once('Application/view/RunningView.php');
include_once('Application/view/RunView.php');
include_once('Application/view/Messages.php');

include_once('Application/controller/RunController.php');

include_once('Application/model/Run.php');
include_once('Application/model/RunDAL.php');
include_once('Application/model/Database.php');
include_once('Application/model/SessionStore.php');


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
      $this->renderLoginPage($loggedInUser, $view, $isLoggedIn);
    } else {
      return $this->layoutView->render($isLoggedIn, $view, $this->dateView);
    }
  }

  private function renderLoginPage($user, $view, $isLoggedIn)
  {
    $session = new \Application\Model\SessionStore();
    $this->runningView = new \Application\View\RunningView($session);
    $runDAL = new \Application\Model\RunDAL($user);
    $runView = new \Application\View\RunView($runDAL->getRuns());
    $this->runController = new \Application\Controller\RunController($this->runningView, $runDAL, $session);

    if ($session->hasStoredMessage()) {
      $this->runningView->setMessage($session->getStoredMessage());
      $session->unsetMessage();
    }

    $this->runController->userTriesToAddRun($user);
    $this->runController->userWantsToDeleteRun($runView);
    $this->runController->userWantsToEditRun($runView);

    $runDAL->updateRuns($user);
    $runView->updateRuns($runDAL->getRuns());

    return $this->layoutView->render($isLoggedIn, $view, $this->dateView, $this->runningView, $runView);
  }
}
