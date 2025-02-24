<?php
// app/controller/CandidatureController.php

namespace App\Controller;

use App\Controller\BaseController;
use PDO;
use PDOException;

// Inclure la classe Database (définie dans app/config/database.php, sans namespace)
require_once __DIR__ . '/../config/database.php';

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
        // Utiliser \Database pour accéder à la classe globale
        $pdo = \Database::getInstance();
        
        if ($userRole === 'Admin') {
            $sql = "SELECT candidature.id, entreprise.nom AS entreprise, offre.titre,
                           candidature.date_soumission, candidature.cv, candidature.lettre, candidature.statut
                    FROM candidature
                    INNER JOIN offre ON candidature.offre_id = offre.id
                    INNER JOIN entreprise ON offre.entreprise_id = entreprise.id
                    ORDER BY candidature.date_soumission DESC";


            $stmt = $pdo->query($sql);
        } else {
            $sql = "SELECT candidature.id, entreprise.nom AS entreprise, offre.titre,
                           candidature.date_soumission, candidature.cv, candidature.lettre, candidature.statut
                    FROM candidature
                    INNER JOIN offre ON candidature.offre_id = offre.id
                    INNER JOIN entreprise ON offre.entreprise_id = entreprise.id
                    WHERE candidature.user_id = :user_id
                    ORDER BY candidature.date_soumission DESC";


            $stmt = $pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
        }
        $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->render('candidatures/index.php', ['candidatures' => $candidatures]);
    }

    /**
     * Postuler à une offre avec CV et lettre de motivation.
     */
    public function postuler() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            session_start();
            
            if (!isset($_SESSION['user']['id'])) {
                die("Erreur : utilisateur non connecté.");
            }
    
            $userId = $_SESSION['user']['id'];
            $offreId = $_POST['offre_id'];
            $dateCandidature = date('Y-m-d');
    
            // Vérification et création du dossier d'upload si nécessaire
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
                $cvTmpPath = $_FILES['cv']['tmp_name'];
                $cvName = basename($_FILES['cv']['name']);
                $cvDestination = $uploadDir . time() . "_" . $cvName;
    
                if (!move_uploaded_file($cvTmpPath, $cvDestination)) {
                    die("Erreur lors du téléchargement du fichier.");
                }
            } else {
                die("Erreur : veuillez fournir un CV au format PDF.");
            }
    
            $lettreMotivation = isset($_POST['lettre_motivation']) ? trim($_POST['lettre_motivation']) : '';
    
            try {
                // Utiliser \Database pour accéder à la classe Database globale
                $pdo = \Database::getInstance();
    
                $sql = "INSERT INTO candidature (user_id, offre_id, cv, lettre, date_soumission) 
                        VALUES (:user_id, :offre_id, :cv, :lettre, :date_soumission)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userId,
                    ':offre_id' => $offreId,
                    ':cv' => 'uploads/' . time() . "_" . $cvName, // Chemin relatif
                    ':lettre' => $lettreMotivation,
                    ':date_soumission' => $dateCandidature
                ]);

    
                header("Location: /index.php?controller=offre&action=detail&id=$offreId&success=1");
                exit();
            } catch (PDOException $e) {
                die("Erreur lors de la candidature : " . $e->getMessage());
            }
        }
    }
}
