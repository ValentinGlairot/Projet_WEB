<?php
// app/controller/EntrepriseController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class EntrepriseController extends BaseController {

    /**
     * Affiche la liste des entreprises.
     */
    public function index() {
        $pdo = \Database::getInstance();
        // Exemple de requête simple pour récupérer toutes les entreprises
        $stmt = $pdo->query("SELECT * FROM entreprise ORDER BY nom ASC");
        $entreprises = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('entreprises/index.php', ['entreprises' => $entreprises]);
    }
    
    /**
     * Affiche les détails d'une entreprise.
     *
     * @param int $id
     */
    public function details($id) {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
        $stmt->execute([$id]);
        $entreprise = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$entreprise) {
            echo "Entreprise introuvable.";
            exit;
        }
        $this->render('entreprises/details.php', ['entreprise' => $entreprise]);
    }
}
