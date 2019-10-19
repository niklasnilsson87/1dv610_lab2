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
  private $date;
  private $id;

  public function __construct(
    string $username,
    string $distance,
    string $time,
    string $date,
    $id = null,
    string $pace = null
  ) {

    $this->validate($distance, $time, $date);
    $this->username = $username;
    $this->distance = $this->validateDistance($distance);
    $this->time = $this->validateTime($time);
    $this->pace = $this->calculateSplitTime($pace);
    $this->date = $date;
    $this->id = $id;
  }

  private function calculateSplitTime($pace): string
  {
    if ($pace != null) {
      return $pace;
    }

    $timeArray = explode(":", $this->time);

    $hour = $this->checkNumeric($this->checkStrLength($timeArray[0]));
    $min = $this->checkNumeric($this->checkStrLength($timeArray[1]));
    $sek = $this->checkNumeric($this->checkStrLength($timeArray[2]));

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

  private function checkStrLength(string $value)
  {
    if (strlen($value) !== 2) {
      throw new \TimeNotInCorrectFormat;
    }
    return $value;
  }

  private function validateTime(string $time)
  {
    $countColon = substr_count($time, ':');
    if ($countColon !== 2) {
      throw new \TimeNotInCorrectFormat;
    }

    return $time;
  }

  private function checkNumeric(string $value)
  {
    if (!is_numeric($value)) {
      throw new \NotNumeric;
    }
    return $value;
  }

  private function validateDistance(string $distance)
  {

    if (strpos($distance, ',') !== false) {
      $distance = str_replace(',', '.', $distance);
    }

    $distance = $this->checkNumeric($distance);

    return $distance;
  }

  private function validate(string $distance, string $time, string $date): void
  {
    if (empty($this->filtered($distance)) && empty($this->filtered($time)) && empty($this->filtered($date))) {
      throw new \RequiredFields;
    }

    if (empty($this->filtered($distance))) {
      throw new \DistanceEmpty;
    }

    if (empty($this->filtered($time))) {
      throw new \TimeEmpty;
    }

    if (empty($this->filtered($date))) {
      throw new \dateEmpty;
    }

    $this->validateHTML($distance);
    $this->validateHTML($time);
    $this->validateHTML($date);
  }

  private function validateHTML(string $value): void
  {
    if ($value != strip_tags($value)) {
      throw new \ContainsHTMLTag;
    }
  }

  private function filtered(string $rawString): string
  {
    return trim(htmlentities($rawString));
  }

  public function getUsername(): string
  {
    return $this->username;
  }

  public function getDistance(): string
  {
    return $this->distance;
  }

  public function getTime(): string
  {
    return $this->time;
  }

  public function getPace(): string
  {
    return $this->pace;
  }

  public function getdate(): string
  {
    return $this->date;
  }

  public function getID()
  {
    return $this->id;
  }
}
