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
      $session = new \Application\Model\SessionStore();
      $this->runningView = new \Application\View\RunningView($session);
      $runDAL = new \Application\Model\RunDAL($loggedInUser);
      $runView = new \Application\View\RunView($runDAL->getRuns());
      $this->runController = new \Application\Controller\RunController($this->runningView, $runDAL, $session);

      if ($session->hasStoredMessage()) {
        $this->runningView->setMessage($session->getStoredMessage());
        $session->unsetMessage();
      }

      $newRunAdded = $this->runController->tryToAddRun($loggedInUser);
      if ($newRunAdded) {
        $runView->updateRuns($runDAL->updateRuns($loggedInUser));
      }

      $runDeleted = $this->runController->tryToDeleteRun($runView);
      if ($runDeleted) {
        $runDAL->updateRuns($loggedInUser);
        $runView->updateRuns($runDAL->getRuns());
      }

      $this->runController->userWantsToEditRun($runView);


      return $this->layoutView->render($isLoggedIn, $view, $this->dateView, $this->runningView, $runView);
    }

    return $this->layoutView->render($isLoggedIn, $view, $this->dateView);
  }
}
