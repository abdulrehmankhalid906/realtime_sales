<?php

namespace App\Core;

use SQLite3;

class DBConnection
{
    protected SQLite3 $db;

    public function __construct()
    {
        $DbPath = __DIR__ . '/../../db/database.sqlite';

        if (!file_exists($DbPath)) {
            return "Database not found at: $DbPath";
        }

        $this->db = new SQLite3($DbPath);
    }

    public function getDB(): SQLite3
    {
        return $this->db;
    }
}