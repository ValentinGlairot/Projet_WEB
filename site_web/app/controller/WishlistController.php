<?php
// app/controller/WishlistController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class WishlistController extends BaseController
{

    /**
     * Affiche la wishlist de l'utilisateur connecté.
     */
    public function index()
    {
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

    public function add()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['offre_id'])) {
            $user_id = $_SESSION['user']['id'];
            $offre_id = intval($_POST['offre_id']);

            $pdo = Database::getInstance();

            // Vérifier si l'offre est déjà dans la wishlist
            $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND offre_id = ?");
            $stmt->execute([$user_id, $offre_id]);

            if ($stmt->fetch()) {
                $_SESSION['error'] = "Cette offre est déjà dans votre wishlist.";
            } else {
                // Ajouter à la wishlist
                $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, offre_id) VALUES (?, ?)");
                $stmt->execute([$user_id, $offre_id]);
                $_SESSION['message'] = "Offre ajoutée à la wishlist !";
            }

            header("Location: " . BASE_URL . "index.php?controller=wishlist&action=index");
            exit;
        } else {
            $_SESSION['error'] = "Une erreur est survenue.";
            header("Location: " . BASE_URL . "index.php?controller=offre&action=index");
            exit;
        }
    }


    public function remove()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wishlist_id = $_POST['wishlist_id'];
            $pdo = \Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = ?");
            $stmt->execute([$wishlist_id]);
            header("Location: " . BASE_URL . "index.php?controller=wishlist&action=index");
            exit;
        }
    }
}
