<?php
// app/controller/ContactController.php

namespace App\Controller;

use App\Controller\BaseController;

class ContactController extends BaseController {

    /**
     * Affiche le formulaire de contact.
     */
    public function index() {
        $this->render('contact/index.php');
    }
    
    /**
     * Traite l'envoi du formulaire de contact.
     */
    public function send() {
        session_start();
    
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $_SESSION["error"] = "Méthode non autorisée.";
            header("Location: " . BASE_URL . "index.php?controller=contact&action=index");
            exit;
        }
    
        $nom     = htmlspecialchars($_POST["nom"]);
        $email   = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        $message = htmlspecialchars($_POST["message"]);
    
        if (!$email) {
            $_SESSION["error"] = "Email invalide.";
            header("Location: " . BASE_URL . "index.php?controller=contact&action=index");
            exit;
        }
    
        try {
            $pdo = \Database::getInstance();
            $stmt = $pdo->prepare("INSERT INTO contact_messages (nom, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $message]);
    
            $_SESSION["success"] = "Votre message a bien été envoyé et enregistré.";
        } catch (\PDOException $e) {
            $_SESSION["error"] = "Erreur lors de l'enregistrement du message.";
        }
    
        header("Location: " . BASE_URL . "index.php?controller=contact&action=index");
        exit;
    }

    public function messages() {
        session_start();
    
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: " . BASE_URL . "index.php?controller=contact&action=index");
            exit;
        }
    
        $pdo = \Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY date_envoi DESC");
        $messages = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        $this->render('contact/messages.php', ['messages' => $messages]);
    }
    
    
    
    
}
