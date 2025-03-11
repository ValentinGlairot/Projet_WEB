<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Model\Offre;
use Database;

class OffreController extends BaseController
{

    private $offreModel;

    public function __construct()
    {
        $this->offreModel = new Offre(); 
    }

    /**
     * Page de gestion des offres
     */
    public function gererOffres()
    {
        session_start();

        $pdo = Database::getInstance();

        // Récupérer toutes les offres
        $stmt = $pdo->query("SELECT offre.id, offre.titre, offre.remuneration, offre.date_debut, offre.date_fin, entreprise.nom AS entreprise
                             FROM offre
                             JOIN entreprise ON offre.entreprise_id = entreprise.id
                             ORDER BY offre.id DESC");

        $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Affichage de la page "Gérer les Offres"
        $this->render('offres/gerer.php', ['offres' => $offres]);
    }

    /**
     * Modifier une offre
     */
    public function modifier($id)
    {
        session_start();

        if (!$id) {
            die("Erreur : ID manquant pour modifier une offre.");
        }

        $pdo = \Database::getInstance();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupérer les nouvelles données du formulaire
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);
            $remuneration = trim($_POST['remuneration']);
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];

            // Vérifier que tous les champs sont remplis
            if (empty($titre) || empty($description) || empty($remuneration) || empty($date_debut) || empty($date_fin)) {
                $_SESSION["error"] = "Tous les champs sont requis.";
                header("Location: " . BASE_URL . "index.php?controller=offre&action=modifier&id=" . $id);
                exit;
            }

            // Mise à jour en base de données
            try {
                $stmt = $pdo->prepare("UPDATE offre SET titre = ?, description = ?, remuneration = ?, date_debut = ?, date_fin = ? WHERE id = ?");
                $stmt->execute([$titre, $description, $remuneration, $date_debut, $date_fin, $id]);

                $_SESSION["success"] = "L'offre a été mise à jour avec succès.";
                header("Location: " . BASE_URL . "index.php?controller=offre&action=gererOffres");
                exit;
            } catch (\PDOException $e) {
                $_SESSION["error"] = "Erreur lors de la mise à jour : " . $e->getMessage();
                header("Location: " . BASE_URL . "index.php?controller=offre&action=modifier&id=" . $id);
                exit;
            }
        }

        // Si ce n'est pas une requête POST, afficher la page de modification avec l'offre existante
        $stmt = $pdo->prepare("SELECT * FROM offre WHERE id = ?");
        $stmt->execute([$id]);
        $offre = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$offre) {
            die("Erreur : Offre introuvable.");
        }

        $this->render('offres/modifier.php', ['offre' => $offre]);
    }


    /**
     * Supprimer une offre
     */
    public function supprimer($id)
    {
        if (!$id) {
            die("Erreur : ID manquant pour supprimer une offre.");
        }

        // Vérifier si l'offre existe
        $offre = $this->offreModel->findById($id);
        if (!$offre) {
            die("Erreur : Offre introuvable.");
        }

        // Supprimer l'offre
        $deleted = $this->offreModel->deleteById($id);
        if ($deleted) {
            header("Location: " . BASE_URL . "index.php?controller=offre&action=gererOffres");
            exit;
        } else {
            die("Erreur : Échec de la suppression de l'offre.");
        }
    }

    /**
     * Liste des offres
     */
    public function index()
    {
        session_start();
        $pdo = \Database::getInstance();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("SELECT offre.id, offre.titre, offre.remuneration, entreprise.nom AS entreprise FROM offre JOIN entreprise ON offre.entreprise_id = entreprise.id ORDER BY offre.id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('offres/index.php', ['offres' => $offres, 'page' => $page]);
    }

    public function detail($id)
    {
        if (!$id) {
            die("Erreur : ID de l'offre manquant.");
        }

        // Charger l'offre depuis la base de données
        $offre = $this->offreModel->findById($id);
        if (!$offre) {
            die("Erreur : Offre introuvable.");
        }

        // Affichage de la page de détail
        $this->render('offres/detail.php', ['offre' => $offre]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);
            $entreprise_id = intval($_POST['entreprise_id']);
            $remuneration = trim($_POST['remuneration']);
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            if (!empty($titre) && !empty($description)) {
                $offre = new Offre();
                $offre->titre = $titre;
                $offre->description = $description;
                $offre->entreprise_id = $entreprise_id;
                $offre->remuneration = $remuneration;
                $offre->date_debut = $date_debut;
                $offre->date_fin = $date_fin;
                if ($offre->save()) {
                    header("Location: " . BASE_URL . "index.php?controller=offre&action=index");
                    exit;
                }
            } else {
                $_SESSION['error'] = "Tous les champs sont requis.";
            }
        }
        $this->render('offres/create.php');
    }

    public function search()
    {
        session_start();
        $pdo = Database::getInstance();

        // Vérifier si un mot-clé est envoyé
        $motcle = isset($_GET['motcle']) ? trim($_GET['motcle']) : '';

        if (!empty($motcle)) {
            // Recherche dans les offres par titre ou description
            $stmt = $pdo->prepare("
                SELECT offre.id, offre.titre, offre.description, offre.remuneration, entreprise.nom AS entreprise 
                FROM offre 
                JOIN entreprise ON offre.entreprise_id = entreprise.id
                WHERE offre.titre LIKE :motcle OR offre.description LIKE :motcle
                ORDER BY offre.id DESC
            ");
            $stmt->execute(['motcle' => "%$motcle%"]);
            $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            // Si aucun mot-clé, renvoyer toutes les offres
            $stmt = $pdo->query("
                SELECT offre.id, offre.titre, offre.description, offre.remuneration, entreprise.nom AS entreprise 
                FROM offre 
                JOIN entreprise ON offre.entreprise_id = entreprise.id
                ORDER BY offre.id DESC
            ");
            $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // Vue avec les résultats de recherche
        $this->render('offres/index.php', ['offres' => $offres, 'motcle' => $motcle]);
    }

}
