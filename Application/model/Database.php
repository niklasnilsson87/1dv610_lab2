<?php

namespace Application\Model;

include_once("LocalSettings.php");
include_once("ProductionSettings.php");

class Database
{
  private $connection;
  private $settings;

  private static $LOCALHOST = 'localhost';
  private static $SERVER_NAME = 'SERVER_NAME';

  public function __construct()
  {
    $serverAdress = $_SERVER[self::$SERVER_NAME];
    if ($serverAdress == self::$LOCALHOST) {
      $this->settings = new \Login\Model\LocalSettings();
    } else {
      $this->settings = new \Login\Model\ProductionSettings();
    }

    $this->connection = new \mysqli(
      $this->settings->server_name,
      $this->settings->db_name,
      $this->settings->db_password,
      $this->settings->database
    );
  }

  public function saveRun(\Application\Model\Run $runToSave, string $name): void
  {
    $dist = $runToSave->getDistance();
    $time = $runToSave->getTime();
    $pace = $runToSave->getPace();
    $desc = $runToSave->getdate();

    $sql = "INSERT INTO runs (username, distance, time, pace, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('sssss', $name, $dist, $time, $pace, $desc);
    $stmt->execute();
  }

  public function updateRun(\Application\Model\Run $runToSave, string $name): void
  {
    $dist = $runToSave->getDistance();
    $time = $runToSave->getTime();
    $pace = $runToSave->getPace();
    $desc = $runToSave->getdate();
    $id = $runToSave->getID();

    $sql = "UPDATE runs SET username=?, distance=?, time=?, pace=?, date=? WHERE id=?;";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('ssssss', $name, $dist, $time, $pace, $desc, $id);
    $stmt->execute();
  }

  public function loadRuns(string $username): array
  {
    $sql = "SELECT * FROM runs WHERE username=?;";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    $runsFromDatabase = array();
    while ($row = $res->fetch_assoc()) {
      $runsFromDatabase[] =
        new \Application\Model\Run(
          $row["username"],
          $row["distance"],
          $row["time"],
          $row["date"],
          $row["id"],
          $row["pace"]
        );
    }

    return $runsFromDatabase;
  }

  public function deleteRun(string $id): void
  {
    $sql = "DELETE FROM runs WHERE id=?;";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
  }
}
