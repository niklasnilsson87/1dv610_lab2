<?php

class LoginModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
}