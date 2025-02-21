<?php
namespace App\Controller;

use App\Controller\BaseController;
use App\Model\Entreprise;
use Database;

class EntrepriseController extends BaseController {

    public function index() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM entreprise ORDER BY nom ASC");
        $entreprises = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('entreprises/index.php', ['entreprises' => $entreprises]);
    }

    public function details($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
        $stmt->execute([$id]);
        $entreprise = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$entreprise) {
            die("Entreprise introuvable.");
        }
        $this->render('entreprises/details.php', ['entreprise' => $entreprise]);
    }

    public function creer() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $ville = trim($_POST["ville"]);
            $secteur = trim($_POST["secteur"]);
            $taille = trim($_POST["taille"]);

            if (!empty($nom) && !empty($ville) && !empty($secteur) && !empty($taille)) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("INSERT INTO entreprise (nom, ville, secteur, taille) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $ville, $secteur, $taille]);

                header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
                exit;
            }
        }
        $this->render('entreprises/gestion.php');
    }

    public function modifier($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
        $stmt->execute([$id]);
        $entreprise = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$entreprise) {
            die("Entreprise introuvable.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $ville = trim($_POST["ville"]);
            $secteur = trim($_POST["secteur"]);
            $taille = trim($_POST["taille"]);

            $stmt = $pdo->prepare("UPDATE entreprise SET nom = ?, ville = ?, secteur = ?, taille = ? WHERE id = ?");
            $stmt->execute([$nom, $ville, $secteur, $taille, $id]);

            header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
            exit;
        }

        $this->render('entreprises/modifier.php', ['entreprise' => $entreprise]);
    }

    public function supprimer() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];

            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM entreprise WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
            exit;
        }
    }
}
