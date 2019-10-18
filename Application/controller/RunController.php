<?php

namespace Application\Controller;

class RunController
{
  private $runningView;
  private $runDAL;
  private $session;

  public function __construct(\Application\View\RunningView $rv, \Application\Model\RunDAL $rd, \Application\Model\SessionStore $ss)
  {
    $this->session = $ss;
    $this->runDAL = $rd;
    $this->runningView = $rv;
  }

  public function tryToAddRun(string $username)
  {
    try {
      if ($this->runningView->userWantsToSubmitRun()) {

        $newRun = $this->runningView->getNewRun($username);
        $idExist = $this->runDAL->idExist($newRun);

        if ($idExist) {
          $this->runDAL->updateRun($newRun, $username);
          $this->session->saveMessage(\Application\View\Messages::UPDATE_RUN);
          $this->session->unsetSession();
          $this->backToIndex();
        } else {
          $this->runDAL->saveRun($newRun, $username);
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

  public function tryToDeleteRun(\Application\View\RunView $runView)
  {
    if ($runView->userWantsToDeleteRun()) {
      $id = $runView->getRunId();
      $this->runDAL->deleteRun($id);
      $this->runningView->setMessage(\Application\View\Messages::DELETE_RUN);
      return true;
    }
  }

  public function userWantsToEditRun(\Application\View\RunView $runView)
  {
    $this->runningView->setEdit($runView->getEditRun());
    $edit = $this->runningView->userWantsToEditRun();

    if ($edit) {
      $id = $runView->getRunId();
      $run = $this->runDAL->getRunById($id);
      $this->session->saveSessionRun($run);
    }
  }

  public function backToIndex()
  {
    return header('Location: ?');
  }
}
