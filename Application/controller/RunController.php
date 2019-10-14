<?php

namespace Application\Controller;

class RunController
{
  private $runningView;
  private $runStorage;
  private $session;

  public function __construct(\Application\View\RunningView $rv, \Application\Model\RunStorage $runStorage, \Application\Model\SessionStore $sessionStore)
  {
    $this->session = $sessionStore;
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
          $this->session->saveMessage(\Application\View\Messages::UPDATE_RUN);
          $this->session->unsetSession();
          $this->backToIndex();
        } else {
          $this->runStorage->saveRun($newRun, $username);
          $this->session->saveMessage(\Application\View\Messages::ADD_RUN);
          $this->session->unsetSession();
          $this->backToIndex();
        }

        return true;
      }
    } catch (\RequiredFields $e) {
      $this->runningView->errorMessage(\Application\View\Messages::EMPTY_RUN_FIELDS);
    } catch (\DistanceEmpty $e) {
      $this->runningView->errorMessage(\Application\View\Messages::DISTANCE_IN_KM);
    } catch (\TimeEmpty $e) {
      $this->runningView->errorMessage(\Application\View\Messages::TIME_IN_CORRECT_FORMAT);
    } catch (\dateEmpty $e) {
      $this->runningView->errorMessage(\Application\View\Messages::ENTER_date);
    } catch (\NotNumeric $e) {
      $this->runningView->errorMessage(\Application\View\Messages::NUMERIC_VALUE);
    } catch (\TimeNotInCorrectFormat $e) {
      $this->runningView->errorMessage(\Application\View\Messages::NOT_CORRECT_FORMAT);
    } catch (\ContainsHTMLTag $e) {
      $this->runningView->errorMessage(\Application\View\Messages::INVALID_CHARACTERS);
    }
  }

  public function tryToDeleteRun($runView)
  {
    if ($runView->userWantsToDeleteRun()) {
      $id = $runView->getRunId();
      $this->runStorage->deleteRun($id);
      $this->runningView->setMessage(\Application\View\Messages::DELETE_RUN);
      $this->session->unsetSession();
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
      $this->session->saveSessionRun($run);
    }
  }

  public function backToIndex()
  {
    return header('Location: ?');
  }
}
