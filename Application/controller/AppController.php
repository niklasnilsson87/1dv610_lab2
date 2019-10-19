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

/*
* Class to inizialize the Run Tracker Application.
*/

class AppController
{
  private $login;

  private $layoutView;
  private $dateView;

  private $runController;

  public function __construct(\Login\Controller\MainController $login)
  {
    $this->login = $login;
    $this->layoutView = new \Application\View\LayoutView();
    $this->dateView = new \Application\View\DateTimeView();
  }

  public function startApp()
  {
    $authenticationView = $this->login->startLogin();

    $isLoggedIn = $this->login->isAuthenticated();
    $loggedInUser = $this->login->getUsername();

    if ($isLoggedIn) {
      $this->renderApplicationPage($loggedInUser, $authenticationView, $isLoggedIn);
    } else {
      $this->renderAuthenticationPage($authenticationView, $isLoggedIn);
    }
  }

  private function renderApplicationPage(string $user, \Login\View\IView $authenticationView, bool $isLoggedIn)
  {
    $session = new \Application\Model\SessionStore();
    $runDAL = new \Application\Model\RunDAL($user);

    $runningView = new \Application\View\RunningView($session);
    $runView = new \Application\View\RunView($runDAL->getRuns());
    $this->runController = new \Application\Controller\RunController($runningView, $runDAL, $session);

    $this->checkForSavedMessage($session, $runningView);

    $this->runController->userTriesToAddRun($user);
    $this->runController->userWantsToDeleteRun($runView);
    $this->runController->userWantsToEditRun($runView);

    $runDAL->updateRuns($user);
    $runView->updateRuns($runDAL->getRuns());

    return $this->layoutView->render($isLoggedIn, $authenticationView, $this->dateView, $runningView, $runView);
  }

  private function renderAuthenticationPage(\Login\View\IView $authenticationView, bool $isLoggedIn)
  {
    return $this->layoutView->render($isLoggedIn, $authenticationView, $this->dateView);
  }

  private function checkForSavedMessage(\Application\Model\SessionStore $session, \Application\View\RunningView $runningView): void
  {
    if ($session->hasStoredMessage()) {
      $runningView->setMessage($session->getStoredMessage());
      $session->unsetMessage();
    }
  }
}
