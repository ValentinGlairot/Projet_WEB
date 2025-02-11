<?php
session_start();
require_once '../config/database.php';
header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "Non autorisé."]);
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion."]);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === "create") {
    $stmt = $conn->prepare("INSERT INTO entreprise (nom, secteur, ville, taille) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST["nom"], $_POST["secteur"], $_POST["ville"], $_POST["taille"]);
    $stmt->execute();
    echo json_encode(["success" => true, "message" => "Entreprise créée avec succès."]);
}

$conn->close();
?>
