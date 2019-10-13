<?php

namespace Application\Model;

class SessionStore
{
  private static $SESSION_RUN = __CLASS__ . "::RUN";
  private static $SESSION_RUN_ID = __CLASS__ . "::RUN_ID";
  private static $SESSION_RUN_DISTANCE = __CLASS__ . "RUN_DISTANCE";
  private static $SESSION_RUN_TIME = __CLASS__ . "RUN_TIME";
  private static $SESSION_RUN_DESCRIPTION = __CLASS__ . "RUN_DESCRIPTION";

  public function hasStoredRun(): bool
  {
    return isset($_SESSION[self::$SESSION_RUN]);
  }

  public function saveSessionRun($run)
  {
    $_SESSION[self::$SESSION_RUN_ID] = $run->getID();
    $_SESSION[self::$SESSION_RUN_DISTANCE] = $run->getDistance();
    $_SESSION[self::$SESSION_RUN_TIME] = $run->getTime();
    $_SESSION[self::$SESSION_RUN_DESCRIPTION] = $run->getDescription();
    $this->setRunState(true);
  }

  public function setRunState($bool)
  {
    $_SESSION[self::$SESSION_RUN] = $bool;
  }

  public function getID()
  {
    return $_SESSION[self::$SESSION_RUN_ID];
  }

  public function getDistance()
  {
    return $_SESSION[self::$SESSION_RUN_DISTANCE];
  }

  public function getTime()
  {
    return $_SESSION[self::$SESSION_RUN_TIME];
  }

  public function getDescription()
  {
    return $_SESSION[self::$SESSION_RUN_DESCRIPTION];
  }

  public function unsetSession()
  {
    unset($_SESSION[self::$SESSION_RUN]);
    unset($_SESSION[self::$SESSION_RUN_ID]);
    unset($_SESSION[self::$SESSION_RUN_DISTANCE]);
    unset($_SESSION[self::$SESSION_RUN_TIME]);
    unset($_SESSION[self::$SESSION_RUN_DESCRIPTION]);
  }
}
