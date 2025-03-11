<?php
// app/model/BaseModel.php

namespace App\Model;

use \Database; // Accès à la classe Database dans le namespace global
use PDO;

abstract class BaseModel {
    /**
     * Instance PDO pour l'accès à la base de données.
     *
     * @var PDO
     */
    protected $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }
}
?>