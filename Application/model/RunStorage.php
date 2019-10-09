<?php

namespace Application\Model;

class RunStorage
{
  private $db;
  private $runs;

  public function __construct($username)
  {
    $this->db = new \Application\Model\Database();
    $this->runs = $this->db->loadRuns($username);
  }

  public function saveRun(\Application\Model\Run $runToSave, string $name)
  {
    $this->db->saveRun($runToSave, $name);
  }

  public function getRuns()
  {
    return $this->runs;
  }

  public function updateRuns($name)
  {
    $this->runs = $this->db->loadRuns($name);
  }

  public function deleteRun($id)
  {
    $this->db->deleteRun($id);
  }
}
