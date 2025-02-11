<?php
// Activer le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Vérifier si la requête est en POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
    exit;
}

// Vérifier si les champs sont présents
if (empty($_POST["nom"]) || empty($_POST["email"]) || empty($_POST["message"])) {
    echo json_encode(["success" => false, "message" => "Champs manquants."]);
    exit;
}

// Récupération des valeurs
$nom = htmlspecialchars($_POST["nom"]);
$email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars($_POST["message"]);

// Vérifier l'email
if (!$email) {
    echo json_encode(["success" => false, "message" => "Email invalide."]);
    exit;
}

// Simulation d'un enregistrement en BDD (à remplacer par une vraie requête SQL)
$reussi = true; // Mettre false si la requête SQL échoue

if ($reussi) {
    echo json_encode(["success" => true, "message" => "Message bien reçu !"]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur d'enregistrement en BDD."]);
}
