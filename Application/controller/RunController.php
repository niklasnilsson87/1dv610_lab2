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

  public function TryToSubmitRun($username)
  {
    if ($this->runningView->userWantsToSubmitRun()) {
      $newRun = $this->runningView->getNewRun();
      $this->runStorage->saveRun($newRun, $username);
      $this->runningView->updateRun($this->runStorage);
      $this->runningView->setMessage("Successfully added a run");
    }
  }
}
