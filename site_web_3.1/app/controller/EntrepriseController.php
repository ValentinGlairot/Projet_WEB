<?php
namespace App\Controller;

use App\Controller\BaseController;
use Database;

class EntrepriseController extends BaseController
{
    /**
     * Liste + recherche multi-critères + pagination
     */
    public function index()
    {
        $pdo = Database::getInstance();

        // Filtres (GET)
        $nom = isset($_GET['nom']) ? trim($_GET['nom']) : '';
        $ville = isset($_GET['ville']) ? trim($_GET['ville']) : '';
        $secteur = isset($_GET['secteur']) ? trim($_GET['secteur']) : '';

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Construction du WHERE
        $sqlFilter = " WHERE 1=1 ";
        $params = [];
        if ($nom !== '') {
            $sqlFilter .= " AND nom LIKE ? ";
            $params[] = "%$nom%";
        }
        if ($ville !== '') {
            $sqlFilter .= " AND ville LIKE ? ";
            $params[] = "%$ville%";
        }
        if ($secteur !== '') {
            $sqlFilter .= " AND secteur LIKE ? ";
            $params[] = "%$secteur%";
        }

        // Compter le total
        $sqlCount = "SELECT COUNT(*) as total FROM entreprise " . $sqlFilter;
        $stmtCount = $pdo->prepare($sqlCount);
        $stmtCount->execute($params);
        $rowCount = $stmtCount->fetch();
        $total = $rowCount['total'];

        // Récupérer les données paginées
        $sqlData = "SELECT * FROM entreprise " . $sqlFilter . " ORDER BY nom ASC LIMIT ? OFFSET ?";
        $stmtData = $pdo->prepare($sqlData);

        // On bind d’abord les paramètres string de la recherche
        $p = 1;
        foreach ($params as $val) {
            $stmtData->bindValue($p, $val);
            $p++;
        }
        // Puis limit, offset (en PDO::PARAM_INT)
        $stmtData->bindValue($p, $limit, \PDO::PARAM_INT);
        $p++;
        $stmtData->bindValue($p, $offset, \PDO::PARAM_INT);

        $stmtData->execute();
        $entreprises = $stmtData->fetchAll(\PDO::FETCH_ASSOC);

        // Rendu
        $this->render('entreprises/index.php', [
            'entreprises' => $entreprises,
            'page' => $page,
            'total' => $total,
            'limit' => $limit,
            'nom' => $nom,
            'ville' => $ville,
            'secteur' => $secteur
        ]);
    }

    /**
     * Affiche les détails d'une entreprise
     * + moyenne d’évaluation
     * + nombre de stagiaires ayant postulé
     */
    public function details($id)
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT e.*,
                   (SELECT COUNT(DISTINCT c.id)
                    FROM candidature c
                    JOIN offre o2 ON c.offre_id = o2.id
                    WHERE o2.entreprise_id = e.id
                   ) AS nb_stagiaires,
                   (SELECT AVG(ev.note)
                    FROM evaluation_entreprise ev
                    WHERE ev.entreprise_id = e.id
                   ) AS moyenne_eval
            FROM entreprise e
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        $entreprise = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$entreprise) {
            die("Entreprise introuvable.");
        }

        $this->render('entreprises/details.php', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * Créer une entreprise
     */
    public function creer()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $ville = trim($_POST["ville"]);
            $secteur = trim($_POST["secteur"]);
            $taille = trim($_POST["taille"]);

            // TODO: Si besoin, description, email, téléphone...
            // $description = trim($_POST["description"]);
            // $email = trim($_POST["email"]);
            // $telephone = trim($_POST["telephone"]);

            if (!empty($nom) && !empty($ville) && !empty($secteur) && !empty($taille)) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("
                    INSERT INTO entreprise (nom, ville, secteur, taille)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$nom, $ville, $secteur, $taille]);

                header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
                exit;
            }
        }
        $this->render('entreprises/gestion.php');
    }

    /**
     * Modifier une entreprise
     */
    public function modifier($id)
    {
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

            // Etc. (description, email, téléphone)...

            $stmt = $pdo->prepare("
                UPDATE entreprise
                SET nom = ?, ville = ?, secteur = ?, taille = ?
                WHERE id = ?
            ");
            $stmt->execute([$nom, $ville, $secteur, $taille, $id]);

            header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
            exit;
        }

        $this->render('entreprises/modifier.php', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * Supprimer une entreprise
     */
    public function supprimer()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];

            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM entreprise WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
            exit;
        }
    }

    /**
     * Évaluer une entreprise (SFx5)
     */
    public function evaluer($id)
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
            exit;
        }

        // Vérifier l’existence de l’entreprise
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
        $stmt->execute([$id]);
        $entreprise = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$entreprise) {
            die("Entreprise introuvable.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = intval($_POST['note'] ?? 0);
            $commentaire = trim($_POST['commentaire'] ?? '');
            $user_id = $_SESSION['user']['id'];

            $stmtEval = $pdo->prepare("
                INSERT INTO evaluation_entreprise (entreprise_id, user_id, note, commentaire, date_evaluation)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmtEval->execute([$id, $user_id, $note, $commentaire]);

            $_SESSION['message'] = "Évaluation enregistrée avec succès !";
            header("Location: " . BASE_URL . "index.php?controller=entreprise&action=details&id=" . $id);
            exit;
        }

        // Affichage d’un formulaire
        $this->render('entreprises/evaluer.php', [
            'entreprise' => $entreprise
        ]);
    }
}
