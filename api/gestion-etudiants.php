<?php
session_start();
require_once '../config/database.php';
header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "Non autorisé"]);
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$action = $_GET['action'] ?? '';

if ($action === "search") {
    $stmt = $conn->prepare("SELECT nom, prenom, email FROM user WHERE email = ?");
    $stmt->bind_param("s", $_GET["email"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode($result ?: []);
}

if ($action === "create") {
    $stmt = $conn->prepare("INSERT INTO user (nom, prenom, email, role) VALUES (?, ?, ?, 'Étudiant')");
    $stmt->bind_param("sss", $_POST["nom"], $_POST["prenom"], $_POST["email"]);
    $stmt->execute();
    echo json_encode(["message" => "Étudiant ajouté"]);
}

$conn->close();
?>
