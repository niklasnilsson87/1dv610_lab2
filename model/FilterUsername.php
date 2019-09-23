<?php

namespace Login\Model;

include_once('Exceptions.php');


class FilterUsername
{
  private static $MIN_LENGTH = 2;

  private $username;

  public function __construct($toBeFiltered)
  {
    if (empty($toBeFiltered) || strlen($toBeFiltered) < self::$MIN_LENGTH) {
      throw new \UsernameEmpty();
    } else {
      $this->username = $toBeFiltered;
    }
  }

  public function getName()
  {
    return $this->username;
  }
}
