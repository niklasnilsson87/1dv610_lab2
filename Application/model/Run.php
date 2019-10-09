<?php

namespace Application\Model;

class Run
{

  private $username;
  private $distance;
  private $time;
  private $pace;
  private $description;
  private $id;

  public function __construct($username, $distance, $time, $pace, $description, $id = null)
  {
    $this->validate($distance, $time, $description);
    $this->username = $username;
    $this->distance = $distance;
    $this->time = $time;
    $this->pace = $pace;
    $this->description = $description;
    $this->id = $id;
  }

  public function filtered(string $rawString): string
  {
    return trim(htmlentities($rawString));
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
}
