<?php

namespace Application\Model;

include_once("Exceptions.php");

class Run
{
  private static $MIN_PER_HOUR = 60;
  private $username;
  private $distance;
  private $time;
  private $pace;
  private $description;
  private $id;

  public function __construct($username, $distance, $time, $description, $pace = null, $id = null)
  {
    $this->validate($distance, $time, $description);
    $this->username = $username;
    $this->distance = $distance;
    $this->time = $time;
    $this->pace = $this->splitTime($pace);
    $this->description = $description;
    $this->id = $id;
  }

  private function splitTime($pace)
  {

    if ($pace != null) {
      return $pace;
    }

    $timeArray = explode(":", $this->time);
    $hour = $timeArray[0];
    $min = $timeArray[1];
    $sek = $timeArray[2];

    $dividedSek = $sek / self::$MIN_PER_HOUR;
    $hourToMin = $hour * self::$MIN_PER_HOUR;
    $totalTimeInMinutes = $min + $hourToMin + $dividedSek;
    $pace = $totalTimeInMinutes / $this->distance;
    if (is_float($pace)) {
      $decimal = explode(".", strval($pace));
      $dividedSek = $decimal[1];
    }

    $dividedSekToSek = $dividedSek * self::$MIN_PER_HOUR;
    $sec = substr($dividedSekToSek, 0, 2);
    if ($sec == '0') {
      $sec .= '0';
    }

    return floor($pace) . ":" . $sec;
  }

  private function validate($distance, $time, $description)
  {
    if (empty($this->filtered($distance)) && empty($this->filtered($time)) && empty($this->filtered($description))) {
      throw new \RequiredFields;
    }

    if (empty($this->filtered($distance))) {
      throw new \DistanceEmpty;
    }

    if (empty($this->filtered($time))) {
      throw new \TimeEmpty;
    }

    if (empty($this->filtered($description))) {
      throw new \DescriptionEmpty;
    }
  }

  public function filtered($rawString): string
  {
    return trim(htmlentities($rawString));
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getDistance()
  {
    return $this->distance;
  }


  public function getTime()
  {
    return $this->time;
  }


  public function getPace()
  {
    return $this->pace;
  }


  public function getDescription()
  {
    return $this->description;
  }

  public function getID()
  {
    return $this->id;
  }
}
