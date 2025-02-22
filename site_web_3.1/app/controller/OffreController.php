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
     * Liste des offres avec pagination et recherche multi-critères
     */
    public function index()
    {
        session_start();
        $pdo = Database::getInstance();

        // Filtres de recherche
        $motcle = isset($_GET['motcle']) ? trim($_GET['motcle']) : '';
        $filtreCompetences = isset($_GET['competences']) ? trim($_GET['competences']) : '';

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Construire la partie WHERE
        $sqlFilter = " WHERE 1=1 ";
        $params = [];

        if ($motcle !== '') {
            // Cherche dans titre ou description
            $sqlFilter .= " AND (o.titre LIKE ? OR o.description LIKE ?) ";
            $params[] = "%$motcle%";
            $params[] = "%$motcle%";
        }
        if ($filtreCompetences !== '') {
            // Cherche dans competences
            $sqlFilter .= " AND o.competences LIKE ? ";
            $params[] = "%$filtreCompetences%";
        }

        // Nombre total
        $sqlCount = "SELECT COUNT(*) as total FROM offre o " . $sqlFilter;
        $stmtCount = $pdo->prepare($sqlCount);
        $stmtCount->execute($params);
        $resCount = $stmtCount->fetch(\PDO::FETCH_ASSOC);
        $total = $resCount['total'];

        // Données + left join entreprise
        $sqlData = "
            SELECT o.id, o.titre, o.description, o.remuneration, o.date_debut, o.date_fin,
                   o.competences,
                   e.nom as entreprise,
                   (SELECT COUNT(*) FROM candidature c WHERE c.offre_id = o.id) as nb_candidats
            FROM offre o
            JOIN entreprise e ON o.entreprise_id = e.id
            $sqlFilter
            ORDER BY o.id DESC
            LIMIT ? OFFSET ?
        ";
        $stmtData = $pdo->prepare($sqlData);

        // Bind pour la recherche
        $pIndex = 1;
        foreach ($params as $val) {
            $stmtData->bindValue($pIndex, $val);
            $pIndex++;
        }
        // Bind pour limit, offset
        $stmtData->bindValue($pIndex, $limit, \PDO::PARAM_INT);
        $pIndex++;
        $stmtData->bindValue($pIndex, $offset, \PDO::PARAM_INT);

        $stmtData->execute();
        $offres = $stmtData->fetchAll(\PDO::FETCH_ASSOC);

        // Rendu
        $this->render('offres/index.php', [
            'offres' => $offres,
            'page' => $page,
            'total' => $total,
            'limit' => $limit,
            'motcle' => $motcle,
            'competences' => $filtreCompetences
        ]);
    }

    /**
     * Page d'administration : gérer toutes les offres
     */
    public function gererOffres()
    {
        session_start();
        $pdo = Database::getInstance();

        $stmt = $pdo->query("
            SELECT o.id, o.titre, o.remuneration, o.date_debut, o.date_fin,
                   e.nom AS entreprise
            FROM offre o
            JOIN entreprise e ON o.entreprise_id = e.id
            ORDER BY o.id DESC
        ");
        $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('offres/gerer.php', ['offres' => $offres]);
    }

    /**
     * Détail d’une offre
     */
    public function detail($id)
    {
        if (!$id) {
            die("Erreur : ID de l'offre manquant.");
        }
        $offre = $this->offreModel->findById($id);
        if (!$offre) {
            die("Erreur : Offre introuvable.");
        }
        $this->render('offres/detail.php', ['offre' => $offre]);
    }

    /**
     * Créer une nouvelle offre
     */
    public function create()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);
            $entreprise_id = intval($_POST['entreprise_id']);
            $remuneration = trim($_POST['remuneration']);
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $competences = trim($_POST['competences'] ?? '');

            if (!empty($titre) && !empty($description) && $entreprise_id > 0) {
                $offre = new Offre();
                $offre->titre = $titre;
                $offre->description = $description;
                $offre->entreprise_id = $entreprise_id;
                $offre->remuneration = $remuneration;
                $offre->date_debut = $date_debut;
                $offre->date_fin = $date_fin;
                $offre->competences = $competences;

                if ($offre->save()) {
                    header("Location: " . BASE_URL . "index.php?controller=offre&action=index");
                    exit;
                } else {
                    $_SESSION['error'] = "Erreur lors de la sauvegarde de l'offre.";
                }
            } else {
                $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
            }
        }
        $this->render('offres/create.php');
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

        $pdo = Database::getInstance();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);
            $remuneration = trim($_POST['remuneration']);
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $competences = trim($_POST['competences'] ?? '');

            if (empty($titre) || empty($description) || empty($remuneration) ||
                empty($date_debut) || empty($date_fin)) {
                $_SESSION["error"] = "Tous les champs sont requis.";
                header("Location: " . BASE_URL . "index.php?controller=offre&action=modifier&id=" . $id);
                exit;
            }

            try {
                $stmt = $pdo->prepare("
                    UPDATE offre
                    SET titre = ?, description = ?, remuneration = ?,
                        date_debut = ?, date_fin = ?, competences = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $titre, $description, $remuneration,
                    $date_debut, $date_fin, $competences, $id
                ]);

                $_SESSION["success"] = "L'offre a été mise à jour avec succès.";
                header("Location: " . BASE_URL . "index.php?controller=offre&action=gererOffres");
                exit;
            } catch (\PDOException $e) {
                $_SESSION["error"] = "Erreur lors de la mise à jour : " . $e->getMessage();
                header("Location: " . BASE_URL . "index.php?controller=offre&action=modifier&id=" . $id);
                exit;
            }
        }

        // Récup l'offre existante pour l'afficher
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

        $offre = $this->offreModel->findById($id);
        if (!$offre) {
            die("Erreur : Offre introuvable.");
        }

        $deleted = $this->offreModel->deleteById($id);
        if ($deleted) {
            header("Location: " . BASE_URL . "index.php?controller=offre&action=gererOffres");
            exit;
        } else {
            die("Erreur : Échec de la suppression de l'offre.");
        }
    }

    /**
     * Ancienne méthode de recherche (vous pouvez la garder ou la fusionner avec index())
     */
    public function search()
    {
        session_start();
        $pdo = Database::getInstance();

        $motcle = isset($_GET['motcle']) ? trim($_GET['motcle']) : '';

        if (!empty($motcle)) {
            $stmt = $pdo->prepare("
                SELECT o.id, o.titre, o.description, o.remuneration,
                       e.nom as entreprise
                FROM offre o
                JOIN entreprise e ON o.entreprise_id = e.id
                WHERE o.titre LIKE :motcle OR o.description LIKE :motcle
                ORDER BY o.id DESC
            ");
            $stmt->execute(['motcle' => "%$motcle%"]);
            $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("
                SELECT o.id, o.titre, o.description, o.remuneration,
                       e.nom as entreprise
                FROM offre o
                JOIN entreprise e ON o.entreprise_id = e.id
                ORDER BY o.id DESC
            ");
            $offres = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        $this->render('offres/index.php', [
            'offres' => $offres,
            'motcle' => $motcle
        ]);
    }
}
