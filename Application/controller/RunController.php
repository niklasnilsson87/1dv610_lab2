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
        $idExist = $this->runStorage->idExist($newRun);
        if ($idExist) {
          $this->runStorage->updateRun($newRun, $username);
          $this->runningView->setMessage("Successfully updated a run");
        } else {
          $this->runStorage->saveRun($newRun, $username);
          $this->runningView->setMessage("Successfully added a run");
        }
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
    } catch (\NotNumeric $e) {
      $this->runningView->errorMessage("You must enter a numeric value.");
    } catch (\TimeNotInCorrectFormat $e) {
      $this->runningView->errorMessage("Time is not in correct format");
    } catch (\ContainsHTMLTag $e) {
      $this->runningView->errorMessage("Input contains invalid characters.");
    }
  }

  public function tryToDeleteRun($runView)
  {
    if ($runView->userWantsToDeleteRun()) {
      $id = $runView->getRunId();
      $this->runStorage->deleteRun($id);
      $this->runningView->setMessage("Successfully deleted run");
      return true;
    }
  }

  public function userWantsToEditRun($runView)
  {
    $this->runningView->setEdit($runView->getEditRun());
    $edit = $this->runningView->userWantsToEditRun();
    if ($edit) {
      $id = $runView->getRunId();
      $run = $this->runStorage->getRunById($id);
      $this->runningView->setRun($run);
    }
  }
}
