<?php
namespace App\Controller;

use App\Controller\BaseController;

class DashboardController extends BaseController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }

        $this->render('dashboard/index.php', ['user' => $_SESSION['user']]);
    }

    // Statistiques sur les offres
    public function offerStats() {
        $pdo = \Database::getInstance();

        // Nombre total d'offres
        $stmtTotal = $pdo->query("SELECT COUNT(*) as total FROM offre");
        $stats['total_offres'] = $stmtTotal->fetch(\PDO::FETCH_ASSOC)['total'];

        // TOP 5 des offres les plus wishlistées
        $stmtTopWishlist = $pdo->query("
            SELECT o.titre, e.nom as entreprise, COUNT(w.id) as nb
            FROM wishlist w
            JOIN offre o ON w.offre_id = o.id
            JOIN entreprise e ON o.entreprise_id = e.id
            GROUP BY o.id
            ORDER BY nb DESC
            LIMIT 5
        ");
        $stats['top_wishlist'] = $stmtTopWishlist->fetchAll(\PDO::FETCH_ASSOC);

        // Par durée (exemple)
        $stmtDuree = $pdo->query("
            SELECT DATEDIFF(date_fin, date_debut) as duree, COUNT(*) as nb
            FROM offre
            GROUP BY duree
            ORDER BY duree
        ");
        $stats['durees'] = $stmtDuree->fetchAll(\PDO::FETCH_ASSOC);

        // On peut imaginer un champ competences, etc.

        $this->render('dashboard/offerStats.php', ['stats' => $stats]);
    }
}
