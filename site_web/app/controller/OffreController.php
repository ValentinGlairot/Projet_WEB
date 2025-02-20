<?php
// app/controller/OffreController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class OffreController extends BaseController {

    /**
     * Affiche la liste des offres.
     */
    public function index() {
        $pdo = \Database::getInstance();
        $stmt = $pdo->query("SELECT offre.id, offre.titre, offre.remuneration, entreprise.nom AS entreprise 
                             FROM offre 
                             JOIN entreprise ON offre.entreprise_id = entreprise.id 
                             ORDER BY offre.id DESC");
        $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('offres/index.php', ['offres' => $offres]);
    }
    
    /**
     * Affiche le détail d'une offre.
     *
     * @param int $id
     */
    public function detail($id) {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT o.id, o.titre, o.description, o.duree, o.remuneration, e.nom AS entreprise 
                               FROM offre o
                               JOIN entreprise e ON o.entreprise_id = e.id
                               WHERE o.id = ?");
        $stmt->execute([$id]);
        $offre = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$offre) {
            echo "Offre introuvable.";
            exit;
        }
        $this->render('offres/detail.php', ['offre' => $offre]);
    }
    
    /**
     * Crée une nouvelle offre.
     */
    public function create() {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'entreprise'])) {
            header("Location: " . BASE_URL . "login.php");
            exit;
        }
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $titre         = trim($_POST["titre"]);
            $description   = trim($_POST["description"]);
            $entreprise    = trim($_POST["entreprise"]);
            $remuneration  = trim($_POST["remuneration"]);
            $date_debut    = $_POST["date_debut"];
            $date_fin      = $_POST["date_fin"];
            
            if (!empty($titre) && !empty($description) && !empty($entreprise) && !empty($remuneration) && !empty($date_debut) && !empty($date_fin)) {
                $pdo = \Database::getInstance();
                $stmt = $pdo->prepare("INSERT INTO offre (titre, description, duree, remuneration, entreprise_id, date_debut, date_fin) 
                                       VALUES (?, ?, DATEDIFF(?, ?), ?, (SELECT id FROM entreprise WHERE nom = ? LIMIT 1), ?, ?)");
                $success = $stmt->execute([$titre, $description, $date_fin, $date_debut, $remuneration, $entreprise, $date_debut, $date_fin]);
                if ($success) {
                    $_SESSION["message"] = "Offre ajoutée avec succès !";
                    header("Location: " . BASE_URL . "offres/index.php");
                    exit;
                } else {
                    $_SESSION["error"] = "Erreur lors de l'ajout de l'offre.";
                }
            } else {
                $_SESSION["error"] = "Veuillez remplir tous les champs.";
            }
        }
        $this->render('offres/ajout.php');
    }
    
    /**
     * Modifie une offre existante.
     *
     * @param int $id
     */
    public function update($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'entreprise'])) {
            header("Location: " . BASE_URL . "login.php");
            exit;
        }
        $pdo = \Database::getInstance();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $titre         = trim($_POST["titre"]);
            $description   = trim($_POST["description"]);
            $entreprise    = trim($_POST["entreprise"]);
            $remuneration  = trim($_POST["remuneration"]);
            $date_debut    = $_POST["date_debut"];
            $date_fin      = $_POST["date_fin"];
            
            if (!empty($titre) && !empty($description) && !empty($entreprise) && !empty($remuneration) && !empty($date_debut) && !empty($date_fin)) {
                $stmt = $pdo->prepare("UPDATE offre SET titre = ?, description = ?, duree = DATEDIFF(?, ?), remuneration = ?, entreprise_id = (SELECT id FROM entreprise WHERE nom = ? LIMIT 1), date_debut = ?, date_fin = ? WHERE id = ?");
                $success = $stmt->execute([$titre, $description, $date_fin, $date_debut, $remuneration, $entreprise, $date_debut, $date_fin, $id]);
                if ($success) {
                    $_SESSION["message"] = "Offre modifiée avec succès !";
                    header("Location: " . BASE_URL . "offres/index.php");
                    exit;
                } else {
                    $_SESSION["error"] = "Erreur lors de la modification de l'offre.";
                }
            } else {
                $_SESSION["error"] = "Veuillez remplir tous les champs.";
            }
        }
        $stmt = $pdo->prepare("SELECT * FROM offre WHERE id = ?");
        $stmt->execute([$id]);
        $offre = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$offre) {
            echo "Offre introuvable.";
            exit;
        }
        $this->render('offres/modification.php', ['offre' => $offre]);
    }
    
    /**
     * Supprime une offre.
     *
     * @param int $id
     */
    public function delete($id) {
        session_start();
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'entreprise'])) {
            header("Location: " . BASE_URL . "login.php");
            exit;
        }
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM offre WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            $_SESSION["message"] = "Offre supprimée";
        } else {
            $_SESSION["error"] = "Offre introuvable";
        }
        header("Location: " . BASE_URL . "offres/index.php");
        exit;
    }
}
