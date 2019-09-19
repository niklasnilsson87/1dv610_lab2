<?php

namespace Login\Model;

class ProductionSettings {
  public $server_name;
  public $db_name;
  public $db_password;
  public $database;

  public function __construct() {
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);

    $this->server_name = $dbparts['host'];
    $this->db_name = $dbparts['user'];
    $this->db_password = $dbparts['pass'];
    $this->database = ltrim($dbparts['path'],'/');
  }
}