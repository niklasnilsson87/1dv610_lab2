<?php

namespace Application\Model;

include_once("Authentication/model/LocalSettings.php");

class Database
{
  private $connection;
  private $settings;

  public function __construct()
  {

    // Check if localhost
    $serverAdress = $_SERVER['SERVER_NAME'];
    if ($serverAdress == 'localhost') {
      $this->settings = new \Login\Model\LocalSettings();
    }
    // else {
    //   $this->settings = new \Login\Model\ProductionSettings();
    // }

    $this->connection = new \mysqli($this->settings->server_name, $this->settings->db_name, $this->settings->db_password, $this->settings->database);
    // Check connection
    if ($this->connection->connect_errno) {
      printf("Connect failed: %s\n", $this->connection->connect_error);
      exit();
    }
  }

  public function saveRun(\Application\Model\Run $runToSave, string $name)
  {
    $dist = $runToSave->getDistance();
    $time = $runToSave->getTime();
    $pace = $runToSave->getPace();
    $desc = $runToSave->getDescription();

    $sql = "INSERT INTO runs (username, distance, time, pace, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('sssss', $name, $dist, $time, $pace, $desc);
    $stmt->execute();

    if ($sql) {
      echo "success";
    } else {
      echo "fail";
    }
  }

  public function loadRuns($username)
  {
    $sql = "SELECT * FROM runs WHERE username=?;";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    $rows = array();
    while ($row = $res->fetch_assoc()) {
      $rows[] = $row;
    }


    return $rows;
  }
}
