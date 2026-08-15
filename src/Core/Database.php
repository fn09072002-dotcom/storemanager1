<?php

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO {
        if (self::$instance ===null ) {
            try {
                self::$instance = new PDO(
                    "pgsql:host=localhost;dbname=storemanager;port=5432",
                    "postgres",
                    "1234"
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Échec PostgreSQL → on bascule sur SQLite
                self::$instance = new PDO("sqlite:" . __DIR__ . "/../../erp.db");
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->exec("PRAGMA foreign_keys = ON;");
            }
        }
        return self::$instance;
    }
}