<?php
namespace App\Controller;

use App\Controller\BaseController;
use Database;
use App\Model\Entreprise;

class EntrepriseController extends BaseController
{
    /**
     * Liste + recherche multi-critères + pagination
     * => on peut laisser accès public ou exiger connexion.
     *   Ici, je laisse accessible à tous (ou au moins logué).
     */
    public function index()
    {
        session_start();
        // (Optionnel) Bloquer si pas connecté :
        // if (!isset($_SESSION['user'])) {
        //     header("Location: ...");
        //     exit;
        // }

        $pdo = Database::getInstance();

        // Filtres
        $nom = isset($_GET['nom']) ? trim($_GET['nom']) : '';
        $ville = isset($_GET['ville']) ? trim($_GET['ville']) : '';
        $secteur = isset($_GET['secteur']) ? trim($_GET['secteur']) : '';

        // Pagination
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // WHERE
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

        // Count
        $sqlCount = "SELECT COUNT(*) as total FROM entreprise " . $sqlFilter;
        $stmtCount = $pdo->prepare($sqlCount);
        $stmtCount->execute($params);
        $rowCount = $stmtCount->fetch();
        $total = $rowCount['total'];

        // Data
        $sqlData = "SELECT * FROM entreprise
                    " . $sqlFilter . " ORDER BY nom ASC
                    LIMIT ? OFFSET ?";
        $stmtData = $pdo->prepare($sqlData);

        // Bind
        $p = 1;
        foreach ($params as $val) {
            $stmtData->bindValue($p, $val);
            $p++;
        }
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
     * Affiche les détails d'une entreprise + nb_stagiaires & moyenne_eval
     * => accessible à tout utilisateur.
     */
    public function details($id)
    {
        session_start();

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
     * Créer une entreprise => réservé admin/pilote
     */
    public function creer()
    {
        session_start();
        if (
            !isset($_SESSION['user'])
            || !in_array($_SESSION['user']['role'], ['Admin', 'pilote'])
        ) {
            header("Location: " . BASE_URL . "index.php?controller=home&action=index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $ville = trim($_POST["ville"]);
            $secteur = trim($_POST["secteur"]);
            $taille = trim($_POST["taille"]);
            $description = trim($_POST["description"]);
            $email = trim($_POST["email"]);
            $telephone = trim($_POST["telephone"]);

            if (!empty($nom) && !empty($ville) && !empty($secteur) && !empty($taille)) {
                $entreprise = new Entreprise();
                $entreprise->nom = $nom;
                $entreprise->ville = $ville;
                $entreprise->secteur = $secteur;
                $entreprise->taille = $taille;
                $entreprise->description = $description;
                $entreprise->email = $email;
                $entreprise->telephone = $telephone;
                $entreprise->save();

                header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
                exit;
            }
        }

        // On peut soit faire un render vers une vue "creer.php",
        // soit laisser la partie "form creation" dans index, etc.
        $this->render('entreprises/gestion.php');
    }

    /**
     * Modifier une entreprise => réservé admin/pilote
     */
    public function modifier($id)
    {
        session_start();
        if (
            !isset($_SESSION['user'])
            || !in_array($_SESSION['user']['role'], ['Admin', 'pilote'])
        ) {
            header("Location: " . BASE_URL . "index.php?controller=home&action=index");
            exit;
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
        $stmt->execute([$id]);
        $entrepriseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$entrepriseData) {
            die("Entreprise introuvable.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $ville = trim($_POST["ville"]);
            $secteur = trim($_POST["secteur"]);
            $taille = trim($_POST["taille"]);
            $description = trim($_POST["description"]);
            $email = trim($_POST["email"]);
            $telephone = trim($_POST["telephone"]);

            $stmt = $pdo->prepare("
                UPDATE entreprise
                SET nom = ?, ville = ?, secteur = ?, taille = ?,
                    description = ?, email = ?, telephone = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $nom,
                $ville,
                $secteur,
                $taille,
                $description,
                $email,
                $telephone,
                $id
            ]);

            header("Location: " . BASE_URL . "index.php?controller=entreprise&action=index");
            exit;
        }

        $this->render('entreprises/modifier.php', [
            'entreprise' => $entrepriseData
        ]);
    }

    /**
     * Supprimer une entreprise => réservé admin/pilote
     */
    public function supprimer()
    {
        session_start();
        if (
            !isset($_SESSION['user'])
            || !in_array($_SESSION['user']['role'], ['Admin', 'pilote'])
        ) {
            header("Location: " . BASE_URL . "index.php?controller=home&action=index");
            exit;
        }

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
     * Évaluer une entreprise => accessible aux étudiants ?
     *   Ici,  tout user loggué, ou spécifiquement 'etudiant'.
     */
    public function evaluer($id)
    {
        session_start();
        if (
            !isset($_SESSION['user'])
            || $_SESSION['user']['role'] !== 'etudiant'
        ) {
            header("Location: " . BASE_URL . "index.php?controller=home&action=index");
            exit;
        }

        // Vérifier existence entreprise
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
?>