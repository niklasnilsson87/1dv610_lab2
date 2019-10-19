<?php

namespace Login\Model;

include_once('Exceptions.php');

class FilterUsername
{
  private static $MIN_LENGTH = 3;

  private $username;

  public function __construct(string $toBeFiltered)
  {
    if (empty($toBeFiltered) || strlen($toBeFiltered) < self::$MIN_LENGTH) {
      throw new \UsernameEmpty;
    } else {
      $this->username = $toBeFiltered;
    }
  }

  public function getName(): string
  {
    return $this->username;
  }
}
