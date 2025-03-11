<?php
// app/controller/UtilisateurController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class UtilisateurController extends BaseController
{

    /**
     * Page de connexion => pas de restriction
     */
    public function connexion()
    {
        $this->render('utilisateurs/connexion.php');
    }

    /**
     * Page d'inscription => pas de restriction
     */
    public function inscription()
    {
        $this->render('utilisateurs/inscription.php');
    }

    /**
     * Déconnecte (si connecté), pas de check de rôle particulier
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?controller=home&action=index");
        exit();
    }

    /**
     * Traitement login => tout public
     */
    public function login()
    {
        session_start();
        $pdo = Database::getInstance();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            // Vérification
            if (empty($email) || empty($password)) {
                echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs."]);
                exit;
            }

            // Vérifier si l'email existe
            $stmt = $pdo->prepare("SELECT id, nom, prenom, email, role, password FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
                exit;
            }

            // Stocker en session
            $_SESSION["user"] = [
                "id" => $user["id"],
                "nom" => $user["nom"],
                "prenom" => $user["prenom"],
                "email" => $user["email"],
                "role" => $user["role"]
            ];

            // Vérification
            if (!isset($_SESSION["user"])) {
                echo json_encode(["success" => false, "message" => "Erreur lors de la création de la session."]);
                exit;
            }

            // Redirection => dashboard, par exemple
            header("Location: " . BASE_URL . "index.php?controller=dashboard&action=index");
            exit;
        }
    }

    public function register()
    {
        session_start();
        $pdo = Database::getInstance();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom = trim($_POST["nom"]);
            $prenom = trim($_POST["prenom"]);
            $email = trim($_POST["email"]);
            $role = trim($_POST["role"]);
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirm-password"];

            // Vérification des champs obligatoires
            if (empty($nom) || empty($prenom) || empty($email) || empty($role) || empty($password) || empty($confirmPassword)) {
                $error = "Tous les champs sont requis.";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                return;
            }

            // Vérification du format email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email invalide.";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                return;
            }

            // Vérification des mots de passe
            if ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                return;
            }

            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé.";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                return;
            }

            // Hash du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO user (nom, prenom, email, role, password) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$nom, $prenom, $email, $role, $hashedPassword])) {
                header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
                exit();
            } else {
                $error = "Erreur lors de l'inscription.";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                return;
            }
        }
    }


}
?>