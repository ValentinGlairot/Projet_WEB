<?php
namespace App\Controller;

use Database;

class GestionUtilisateursController extends BaseController {
    
    // Affichage de la gestion des utilisateurs
    public function index() {
        $pdo = Database::getInstance();
        
        // Récupérer tous les utilisateurs
        $stmt = $pdo->query("SELECT id, nom, prenom, email, role FROM user ORDER BY id DESC");
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Passer la liste des utilisateurs à la vue
        $this->render('gestion_utilisateurs/index.php', ['users' => $users]);
    }

    // Création d'un utilisateur
    public function create() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $prenom = trim($_POST["prenom"]);
            $email = trim($_POST["email"]);
            $role = trim($_POST["role"]);
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hachage du mot de passe

            if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($role) && !empty($password)) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("INSERT INTO user (nom, prenom, email, role, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $role, $password]);

                $_SESSION["message"] = "Utilisateur ajouté avec succès.";
            } else {
                $_SESSION["error"] = "Veuillez remplir tous les champs.";
            }

            header("Location: " . BASE_URL . "index.php?controller=gestionutilisateurs&action=index");
            exit;
        }
    }

    // Modification d'un utilisateur
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];
            $nom = trim($_POST["nom"]) ?? null;
            $prenom = trim($_POST["prenom"]) ?? null;
            $email = trim($_POST["email"]) ?? null;
            $role = trim($_POST["role"]) ?? null;

            $pdo = Database::getInstance();
            $sql = "UPDATE user SET ";
            $params = [];

            if ($nom) { $sql .= "nom = ?, "; $params[] = $nom; }
            if ($prenom) { $sql .= "prenom = ?, "; $params[] = $prenom; }
            if ($email) { $sql .= "email = ?, "; $params[] = $email; }
            if ($role) { $sql .= "role = ?, "; $params[] = $role; }

            $sql = rtrim($sql, ", ") . " WHERE id = ?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $_SESSION["message"] = "Utilisateur modifié avec succès.";
            header("Location: " . BASE_URL . "index.php?controller=gestionutilisateurs&action=index");
            exit;
        }
    }

    // Suppression d'un utilisateur
    public function delete() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];

            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute([$id]);

            $_SESSION["message"] = "Utilisateur supprimé avec succès.";
            header("Location: " . BASE_URL . "index.php?controller=gestionutilisateurs&action=index");
            exit;
        }
    }

    public function search() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $searchQuery = trim($_POST["search_query"]);
    
            if (!empty($searchQuery)) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare("SELECT * FROM user WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ?");
                $stmt->execute(["%$searchQuery%", "%$searchQuery%", "%$searchQuery%"]);
                $search_result = $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                $search_result = null;
            }
    
            // Récupérer les statistiques des utilisateurs
            $stmtStats = $pdo->query("SELECT 
                COUNT(*) AS total_users,
                SUM(CASE WHEN role = 'etudiant' THEN 1 ELSE 0 END) AS total_etudiants,
                SUM(CASE WHEN role = 'pilote' THEN 1 ELSE 0 END) AS total_pilotes,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS total_admins
            FROM user");
            $stats = $stmtStats->fetch(\PDO::FETCH_ASSOC);
    
            $this->render('gestion_utilisateurs/index.php', ['search_result' => $search_result, 'stats' => $stats]);
        }
    }
    
    

}
?>
