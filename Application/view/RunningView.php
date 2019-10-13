<?php

namespace Application\View;

class RunningView
{

  private static $message =  __CLASS__ . '::Message';
  private static $distance = __CLASS__ . '::Distance';
  private static $time = __CLASS__ . '::Time';
  private static $description = __CLASS__ . '::Description';
  private static $IDRun = __CLASS__ . '::IDRun';
  private static $submitRun = __CLASS__ . '::SubmitRun';

  private static $distanceVal;
  private static $timeVal;
  private static $descriptionVal;
  private static $idVal;
  private static $editRun;

  private static $msg = '';
  private static $errorMessage = '';

  public function response()
  {
    $response = $this->appHeader();
    $response .= $this->createNewRun();
    $response .= $this->successMessage();
    if ($this->userWantsToCreateRun()) {
      if ($this->userWantsToSubmitRun() && strlen(self::$errorMessage) > 0) {
        $response .= $this->generateRunningForm();
        return $response;
      } else if (!$this->userWantsToSubmitRun()) {
        $response .= $this->generateRunningForm();
        return $response;
      }
    }
    var_dump(self::$idVal);
    echo "<br>";
    if (strlen(self::$errorMessage) > 0) {
      $response .= $this->generateRunningForm();
      return $response;
    }

    if ($this->userWantsToEditRun()) {
      $response .= $this->generateRunningForm();
    }

    return $response;
  }

  private function successMessage(): string
  {
    return '
    <div class="message">
      <p id="' . self::$message . '">' . self::$msg . '</p>
    </div>
    ';
  }

  private function appHeader()
  {
    return '
    <h2 class="runHeader">Run Tracker</h2>
    ';
  }

  private function createNewRun()
  {
    if ($this->userWantsToCreateRun() && strlen(self::$msg) <= 0 || $this->userWantsToEditRun()) {
      return '<a class="button" href=?>Cancel new run</a>';
    }

    return '<a class="button" href=?create>Create new run</a>';
  }

  public function generateRunningForm()
  {
    return '
    <form action="" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>' . $this->setFieldsetTitle() . '</legend>
        <p id="' . self::$errorMessage . '">' . self::$errorMessage . '</p>
          <label for="' . self::$distance . '" >Distance (km) :</label>
          <input type="text" name="' . self::$distance . '" id="' . self::$distance . '" value="' . $this->setDistanceValue() . '" />
        
          <label for="' . self::$time . '" >Time format(hh:mm:ss)  :</label>
          <input type="text" size="10" name="' . self::$time . '" id="' . self::$time . '" value="' . $this->setTimeValue() . '" />
      
          <label for="' . self::$description . '" >Description  :</label>
          <input type="text" size="20" name="' . self::$description . '" id="' . self::$description . '" value="' . $this->setdescriptionValue() . '" />
          <br/>
          <input name="' . self::$IDRun . '" type="hidden" value="' . self::$idVal . '" />
          <br/>
          <input id="submit" type="submit" name="' . self::$submitRun . '"  value="Submit Run" />
          <br/>
      </fieldset>
      </form>
    ';
  }

  public function userWantsToCreateRun()
  {
    return isset($_GET['create']);
  }

  public function userWantsToSubmitRun()
  {
    return isset($_POST[self::$submitRun]);
  }

  public function userWantsToEditRun()
  {
    return isset($_POST[self::$editRun]);
  }

  private function hasId()
  {
    return isset($_POST[self::$IDRun]);
  }

  public function setEdit($edit)
  {
    self::$editRun = $edit;
  }

  public function getNewRun($username): \Application\Model\Run
  {
    if ($this->userWantsToSubmitRun()) {
      $distance = $_POST[self::$distance];
      $time = $_POST[self::$time];
      $description = $_POST[self::$description];
      if ($this->hasId()) {
        $id = $_POST[self::$IDRun];
        return new \Application\Model\Run($username, $distance, $time, $description, $id);
      }
      return new \Application\Model\Run($username, $distance, $time, $description);
    }
  }

  public function setMessage(string $message): void
  {
    self::$msg = $message;
  }

  public function errorMessage(string $message): void
  {
    self::$errorMessage = $message;
  }

  private function setFieldsetTitle(): string
  {
    var_dump($this->userWantsToEditRun());
    echo "<br>";
    var_dump($this->hasId());
    return $this->userWantsToEditRun() || $this->hasId()
      ? 'Edit the current Run'
      : 'Keep track of your runs - Enter a compleated run';
  }

  public function setRun(\Application\Model\Run $run): void
  {
    self::$distanceVal = $run->getDistance();
    self::$timeVal = $run->getTime();
    self::$descriptionVal = $run->getDescription();
    self::$idVal = $run->getID();
  }

  public function setTimeValue()
  {
    // var_dump(self::$idVal);
    // var_dump($this->hasId());
    return $this->userWantsToEditRun() || $this->hasId()
      ? self::$timeVal
      : '00:00:00';
  }

  public function setdescriptionValue()
  {
    return $this->userWantsToEditRun() || $this->hasId()
      ? self::$descriptionVal
      : '';
  }

  public function setDistanceValue()
  {
    return $this->userWantsToEditRun() || $this->hasId()
      ? self::$distanceVal
      : '';
  }
}
