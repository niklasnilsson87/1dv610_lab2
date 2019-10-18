<?php

namespace Application\Model;

class RunDAL
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

  public function saveUpdatedRun(\Application\Model\Run $runToSave, string $name)
  {
    $this->db->updateRun($runToSave, $name);
  }

  public function idExist($run)
  {
    return $run->getId() != null;
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

  public function getRunById($id)
  {
    foreach ($this->runs as $key) {
      if ($key->getId() == $id) {
        return $key;
      }
    }
  }
}
