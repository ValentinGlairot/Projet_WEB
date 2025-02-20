<?php
// app/controller/UtilisateurController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class UtilisateurController extends BaseController {

    /**
     * Affiche et traite la connexion.
     */
    public function login() {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email         = trim($_POST['email']);
            $password_saisi = $_POST['password'];
            
            $pdo = \Database::getInstance();
            $stmt = $pdo->prepare("SELECT id, nom, prenom, email, role, password_hash FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user && password_verify($password_saisi, $user['password_hash'])) {
                $_SESSION['user'] = [
                    "id"    => $user["id"],
                    "nom"   => $user["nom"],
                    "prenom"=> $user["prenom"],
                    "email" => $user["email"],
                    "role"  => $user["role"]
                ];
                header("Location: " . BASE_URL . "dashboard/index.php");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
                $this->render('utilisateurs/connexion.php', ['error' => $error]);
            }
        } else {
            $this->render('utilisateurs/connexion.php');
        }
    }
    
    /**
     * Affiche et traite l'inscription.
     */
    public function register() {
        session_start();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nom      = trim($_POST["nom"]);
            $prenom   = trim($_POST["prenom"]);
            $email    = trim($_POST["email"]);
            $role     = trim($_POST["role"]);
            $password = $_POST["password"];
            $confirm  = $_POST["confirm-password"];
            
            if ($password !== $confirm) {
                $error = "Les mots de passe ne correspondent pas.";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                exit;
            }
            
            if (empty($nom) || empty($prenom) || empty($email) || empty($role) || empty($password)) {
                $error = "Veuillez remplir tous les champs";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                exit;
            }
            
            $pdo = \Database::getInstance();
            $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
                exit;
            }
            
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO user (nom, prenom, email, role, password_hash) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$nom, $prenom, $email, $role, $hashed_password])) {
                $_SESSION['message'] = "Inscription réussie";
                header("Location: " . BASE_URL . "utilisateurs/connexion.php");
                exit;
            } else {
                $error = "Erreur lors de l'inscription";
                $this->render('utilisateurs/inscription.php', ['error' => $error]);
            }
        } else {
            $this->render('utilisateurs/inscription.php');
        }
    }
    
    /**
     * Déconnecte l'utilisateur.
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: " . BASE_URL . "utilisateurs/connexion.php");
        exit;
    }
}
