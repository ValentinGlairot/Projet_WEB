<?php
// app/controller/CandidatureController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database; 
use App\Model\Candidature;


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
        
        $this->render('candidatures/index.php', ['candidatures' => $candidatures]);
    }

    public function postuler() {
        session_start();
    
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }
    
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user_id = $_SESSION['user']['id'];
            $offre_id = $_POST['offre_id'];
            $lettre = trim($_POST['lettre']);
    
            // Vérification et création du dossier uploads
            $upload_dir = dirname(__DIR__, 2) . "/public/uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
    
            // Gestion du fichier CV
            if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
                $cv_name = basename($_FILES['cv']['name']);
                $cv_tmp_name = $_FILES['cv']['tmp_name'];
                $cv_destination = $upload_dir . $cv_name;
    
                if (move_uploaded_file($cv_tmp_name, $cv_destination)) {
                    // Sauvegarde en base de données
                    $pdo = \Database::getInstance();
                    $stmt = $pdo->prepare("INSERT INTO candidature (user_id, offre_id, date_soumission, statut, cv, lettre) 
                                           VALUES (?, ?, NOW(), 'en attente', ?, ?)");
                    $stmt->execute([$user_id, $offre_id, $cv_name, $lettre]);
    
                    $_SESSION['message'] = "Candidature envoyée avec succès !";
                    header("Location: " . BASE_URL . "index.php?controller=candidature&action=index");
                    exit;
                } else {
                    $_SESSION['error'] = "Erreur lors du téléchargement du CV.";
                }
            } else {
                $_SESSION['error'] = "Veuillez sélectionner un fichier CV.";
            }
        }
    }
    
    
}
