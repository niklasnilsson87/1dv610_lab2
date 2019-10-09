<?php

namespace Application\Controller;

class RunController
{
  private $runningView;
  private $runStorage;

  public function __construct(\Application\View\RunningView $rv, \Application\Model\RunStorage $runStorage)
  {
    $this->runStorage = $runStorage;
    $this->runningView = $rv;
  }

  public function TryToAddRun($username)
  {
    if ($this->runningView->userWantsToSubmitRun()) {
      $newRun = $this->runningView->getNewRun($username);
      $this->runStorage->saveRun($newRun, $username);
      $this->runningView->setMessage("Successfully added a run");
      return true;
    }
  }

  public function tryToDeleteRun($runView)
  {
    if ($runView->userWantsToDeleteRun()) {
      $id = $runView->getIdToDelete();
      $this->runStorage->deleteRun($id);
      return true;
    }
  }
}
