<?php

namespace Login\Model;

class DatabaseConfig {
  protected $server_name;
  protected $db_name;
  protected $db_password;
  protected $database;

  public function __construct() {

    // Check if localhost
    $serverAdress = $_SERVER['SERVER_NAME'];

    if ($serverAdress == 'localhost') {
    $this->server_name = 'localhost';
    $this->db_name = 'root';
    $this->db_password = '';
    $this->database = 'users';
    } else {
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);

    $this->server_name = $dbparts['host'];
    $this->db_name = $dbparts['user'];
    $this->db_password = $dbparts['pass'];
    $this->database = ltrim($dbparts['path'],'/');
    }
  }
}