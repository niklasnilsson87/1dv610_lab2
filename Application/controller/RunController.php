<?php

namespace Application\Controller;

class RunController
{
  private $runningView;

  public function __construct(\Application\View\RunningView $rv)
  {
    $this->runningView = $rv;
  }

  public function TryToSubmitRun()
  {
    if ($this->runningView->userWantsToSubmitRun()) {
      $newRun = $this->runningView->getNewRun();
      $this->runningView->setRun($newRun);
    }
  }
}
