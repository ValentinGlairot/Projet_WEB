<?php
// app/controller/CandidatureController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database; // Utilisation de la classe Database (sans namespace ici si elle n'est pas placée dans un namespace spécifique)

class CandidatureController extends BaseController {

    /**
     * Affiche la liste des candidatures.
     */
    public function index() {
        session_start();
        if (!isset($_SESSION['user']['id'])) {
            header("Location: " . BASE_URL . "login.php");
            exit;
        }
        
        $userId   = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];
        $pdo = \Database::getInstance();
        
        if ($userRole === 'admin') {
            $sql = "SELECT candidature.id, entreprise.nom AS entreprise, offre.titre, candidature.date_soumission, candidature.statut
                    FROM candidature
                    INNER JOIN offre ON candidature.offre_id = offre.id
                    INNER JOIN entreprise ON offre.entreprise_id = entreprise.id
                    ORDER BY candidature.date_soumission DESC";
            $stmt = $pdo->query($sql);
        } else {
            $sql = "SELECT candidature.id, entreprise.nom AS entreprise, offre.titre, candidature.date_soumission, candidature.statut
                    FROM candidature
                    INNER JOIN offre ON candidature.offre_id = offre.id
                    INNER JOIN entreprise ON offre.entreprise_id = entreprise.id
                    WHERE candidature.user_id = :user_id
                    ORDER BY candidature.date_soumission DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
        }
        $candidatures = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Rendu de la vue avec les candidatures
        $this->render('candidatures/index.php', ['candidatures' => $candidatures]);
    }
}
