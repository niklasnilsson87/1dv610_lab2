<?php

namespace Application\Model;

class RunStorage
{
  private $db;
  private $runs = array();

  public function __construct($username)
  {
    $this->db = new \Application\Model\Database();
    $this->runs = $this->db->loadRuns($username);
  }

  public function saveRun(\Application\Model\Run $runToSave, string $name)
  {
    $this->db->saveRun($runToSave, $name);
    $this->runs[] = $runToSave;
    var_dump($this->runs);
  }

  public function getRuns()
  {
    return $this->runs;
  }

  public function updateRuns($name)
  {
    $this->runs = $this->db->loadRuns($name);
  }
}
