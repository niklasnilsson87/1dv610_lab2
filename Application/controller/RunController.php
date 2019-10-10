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
    try {
      if ($this->runningView->userWantsToSubmitRun()) {
        $newRun = $this->runningView->getNewRun($username);
        $this->runStorage->saveRun($newRun, $username);
        $this->runningView->setMessage("Successfully added a run");
        return true;
      }
    } catch (\RequiredFields $e) {
      $this->runningView->errorMessage("You can not submit an empty run.");
    } catch (\DistanceEmpty $e) {
      $this->runningView->errorMessage("You must enter a distance in km.");
    } catch (\TimeEmpty $e) {
      $this->runningView->errorMessage("You must enter a time in correct format");
    } catch (\DescriptionEmpty $e) {
      $this->runningView->errorMessage("You must enter a description.");
    }
  }

  public function tryToDeleteRun($runView)
  {
    if ($runView->userWantsToDeleteRun()) {
      $id = $runView->getIdToDelete();
      $this->runStorage->deleteRun($id);
      $this->runningView->setMessage("Successfully deleted run");
      return true;
    }
  }
}
