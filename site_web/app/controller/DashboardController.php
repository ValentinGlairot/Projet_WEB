<?php
// app/controller/DashboardController.php

namespace App\Controller;

use App\Controller\BaseController;

class DashboardController extends BaseController
{

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }

        $this->render('dashboard/index.php', ['user' => $_SESSION['user']]);
    }

    public function offerStats()
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM offre");
        $stats = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->render('dashboard/offerStats.php', ['stats' => $stats]);
    }
}
