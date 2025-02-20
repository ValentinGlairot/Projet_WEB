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
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
            exit;
        }
        
        $nom     = htmlspecialchars($_POST["nom"]);
        $email   = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        $message = htmlspecialchars($_POST["message"]);
        
        if (!$email) {
            echo json_encode(["success" => false, "message" => "Email invalide."]);
            exit;
        }
        
        // Ici, vous pouvez enregistrer le message en BDD ou envoyer un email.
        // Pour l'exemple, nous simulons la réussite.
        echo json_encode(["success" => true, "message" => "Message bien reçu !"]);
    }
}
