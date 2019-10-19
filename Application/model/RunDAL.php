<?php

namespace Application\Model;

class RunDAL
{
  private $db;
  private $runs;

  public function __construct(string $username)
  {
    $this->db = new \Application\Model\Database();
    $this->runs = $this->db->loadRuns($username);
  }

  public function saveRun(\Application\Model\Run $runToSave, string $name): void
  {
    $this->db->saveRun($runToSave, $name);
  }

  public function saveUpdatedRun(\Application\Model\Run $runToSave, string $name): void
  {
    $this->db->updateRun($runToSave, $name);
  }

  public function idExist(\Application\Model\Run $run): bool
  {
    return $run->getId() != null;
  }

  public function getRuns(): array
  {
    return $this->runs;
  }

  public function updateRuns(string $name): void
  {
    $this->runs = $this->db->loadRuns($name);
  }

  public function deleteRun(string $id): void
  {
    $this->db->deleteRun($id);
  }

  public function getRunById(string $id): \Application\Model\Run
  {
    foreach ($this->runs as $key) {
      if ($key->getId() == $id) {
        return $key;
      }
    }
  }
}
