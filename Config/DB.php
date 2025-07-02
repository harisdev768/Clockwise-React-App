<?php

namespace App\Config;

use PDO;
use PDOException;

class DB {
    public static function getConnection(): PDO {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=clockwise', 'admin_u_rw', 'Loc@lhost');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}