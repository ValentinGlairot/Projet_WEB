<?php
// app/controller/DashboardController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class DashboardController extends BaseController {

    /**
     * Affiche le tableau de bord.
     */
    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Utilisateur non connecté"]);
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $pdo = \Database::getInstance();
        
        try {
            // Offres créées par l'utilisateur (pour un compte entreprise, par exemple)
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM offre WHERE entreprise_id IN (SELECT id FROM entreprise WHERE id = :user_id)");
            $stmt->execute(['user_id' => $user_id]);
            $offres_creees = $stmt->fetchColumn();
            
            // Candidatures reçues
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM candidature WHERE offre_id IN (SELECT id FROM offre WHERE entreprise_id = :user_id)");
            $stmt->execute(['user_id' => $user_id]);
            $candidatures_recues = $stmt->fetchColumn();
            
            // Nombre d'entreprises partenaires
            $stmt = $pdo->query("SELECT COUNT(DISTINCT entreprise_id) FROM offre");
            $entreprises_partenaire = $stmt->fetchColumn();
            
            // Statistiques sur les compétences
            $stmt = $pdo->query("SELECT secteur, COUNT(*) as total FROM entreprise GROUP BY secteur");
            $stats_competences = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $stats_competences[] = "{$row['secteur']}: {$row['total']}";
            }
            $stats_competences = implode(", ", $stats_competences);
            
            // Statistiques sur la durée des stages
            $stmt = $pdo->query("SELECT duree, COUNT(*) as total FROM offre GROUP BY duree");
            $stats_duree = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $stats_duree[] = "{$row['duree']} mois: {$row['total']}";
            }
            $stats_duree = implode(", ", $stats_duree);
            
            // Top offre en wishlist
            $stmt = $pdo->query("SELECT offre.titre, COUNT(*) as total FROM wishlist 
                                 JOIN offre ON wishlist.offre_id = offre.id 
                                 GROUP BY offre_id ORDER BY total DESC LIMIT 1");
            $top_wishlist = $stmt->fetch(\PDO::FETCH_ASSOC);
            $stats_wishlist = $top_wishlist ? $top_wishlist["titre"] : "Aucune donnée";
            
            $data = [
                "offres_creees"         => $offres_creees,
                "candidatures_recues"    => $candidatures_recues,
                "entreprises_partenaire" => $entreprises_partenaire,
                "stats_competences"      => $stats_competences,
                "stats_duree"          => $stats_duree,
                "stats_wishlist"       => $stats_wishlist
            ];
            
            $this->render('dashboard/index.php', $data);
            
        } catch (\PDOException $e) {
            echo json_encode(["success" => false, "message" => "Erreur BDD: " . $e->getMessage()]);
        }
    }
}
