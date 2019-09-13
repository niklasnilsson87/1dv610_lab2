<?php

namespace Login\Model;

class LoginModel {
    private $db;

    public function __construct(\Login\Model\Database $db) {
        $this->db = $db;
    }
}