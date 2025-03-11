<?php
// app/controller/UtilisateurController.php

namespace App\Controller;

use App\Controller\BaseController;
use Database;

class UtilisateurController extends BaseController
{

    /**
     * Affiche la page de connexion.
     */
    public function connexion()
    {
        $this->render('utilisateurs/connexion.php');
    }

    /**
     * Affiche la page d'inscription.
     */
    public function inscription()
    {
        $this->render('utilisateurs/inscription.php');
    }

    /**
     * Déconnecte l'utilisateur et le redirige vers la page de connexion.
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
        exit;
    }

    public function login()
    {
        session_start();
        $pdo = \Database::getInstance();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            // Vérification des champs
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

            // Stocker l'utilisateur dans la session
            $_SESSION["user"] = [
                "id" => $user["id"],
                "nom" => $user["nom"],
                "prenom" => $user["prenom"],
                "email" => $user["email"],
                "role" => $user["role"]
            ];

            // Vérifier si l'utilisateur est bien stocké dans la session
            if (!isset($_SESSION["user"])) {
                echo json_encode(["success" => false, "message" => "Erreur lors de la création de la session."]);
                exit;
            }

            header("Location: " . BASE_URL . "index.php?controller=dashboard&action=index");
            exit;

        }
    }




}
