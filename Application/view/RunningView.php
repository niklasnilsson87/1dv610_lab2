<?php

namespace Application\View;

class RunningView
{

  private static $message =  __CLASS__ . '::Message';
  private static $distance = __CLASS__ . '::Distance';
  private static $time = __CLASS__ . '::Time';
  private static $description = __CLASS__ . '::Description';
  private static $submitRun = __CLASS__ . '::SubmitRun';
  private static $msg = '';
  private static $errorMessage = '';

  public function response()
  {
    $response = $this->appHeader();

    $response .= $this->createNewRun();
    if ($this->userWantsToCreateRun()) {
      if ($this->userWantsToSubmitRun() && strlen(self::$errorMessage) > 0) {
        $response .= $this->generateRunningForm();
        return $response;
      } else if (!$this->userWantsToSubmitRun()) {
        echo strlen(self::$errorMessage);
        $response .= $this->generateRunningForm();
      }
    }
    return $response;
  }

  public function appHeader()
  {
    return '
    <h2>My Running App!</h2>
    <p id="' . self::$message . '">' . self::$msg . '</p>
    ';
  }

  private function createNewRun()
  {
    if ($this->userWantsToCreateRun() && !$this->userWantsToSubmitRun()) {
      return '<a href=?>Cancel new run<a>';
    } else {
      return '<a href=?create>Create new run<a>';
    }
  }

  public function generateRunningForm()
  {
    return '
    <form action="" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>Keep track of your runs - Enter a compleated run</legend>
        <p id="' . self::$errorMessage . '">' . self::$errorMessage . '</p>
          <label for="' . self::$distance . '" >Distance in km :</label>
          <input type="number" name="' . self::$distance . '" id="' . self::$distance . '" value="" />
        
          <label for="' . self::$time . '" >Time format(hh:mm:ss)  :</label>
          <input type="text" size="10" name="' . self::$time . '" id="' . self::$time . '" value="00:00:00" />
      
          <label for="' . self::$description . '" >Description  :</label>
          <input type="text" size="20" name="' . self::$description . '" id="' . self::$description . '" value="" />
          <br/>
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

  public function getNewRun($username)
  {
    if ($this->userWantsToSubmitRun()) {
      $distance = $_POST[self::$distance];
      $time = $_POST[self::$time];
      $description = $_POST[self::$description];
      return new \Application\Model\Run($username, $distance, $time, $description);
    }
  }

  public function setMessage($message)
  {
    self::$msg = $message;
  }

  public function errorMessage($message)
  {
    self::$errorMessage = $message;
  }
}
