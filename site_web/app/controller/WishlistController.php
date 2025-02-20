<?php
// app/controller/WishlistController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class WishlistController extends BaseController {

    /**
     * Affiche la wishlist de l'utilisateur connecté.
     */
    public function index() {
        session_start();
        if (!isset($_SESSION['user'])) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }
        
        $user_id = $_SESSION['user']['id'];
        $pdo = \Database::getInstance();
        
        // Récupérer les offres présentes dans la wishlist de l'utilisateur
        $stmt = $pdo->prepare("SELECT w.id AS wishlist_id, o.id AS offre_id, o.titre, e.nom AS entreprise 
                               FROM wishlist w
                               JOIN offre o ON w.offre_id = o.id
                               JOIN entreprise e ON o.entreprise_id = e.id
                               WHERE w.user_id = ?");
        $stmt->execute([$user_id]);
        $wishlist = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Rendu de la vue avec les données de la wishlist
        $this->render('wishlist/index.php', ['wishlist' => $wishlist]);
    }
}
