<?php

class LoginModel {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;

    }
}