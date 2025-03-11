<?php
// app/config/database.php

class Database
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            $host = 'localhost';
            $dbname = 'projet_web';
            $username = 'root';
            $password = '';
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
?>