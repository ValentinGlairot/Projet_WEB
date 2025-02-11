<?php
session_start();
require_once '../config/database.php';
header("Content-Type: application/json");

// Vérification de l'authentification (seuls les admins peuvent modifier les pilotes)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    echo json_encode(["success" => false, "message" => "Non autorisé"]);
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$action = $_GET['action'] ?? '';

// SUPPRIMER UN PILOTE
if ($action === "delete" && isset($_GET["id"])) {
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ? AND role = 'Pilote'");
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Pilote supprimé"]);
    } else {
        echo json_encode(["success" => false, "message" => "Pilote introuvable"]);
    }
    $stmt->close();
}

// CRÉER UN PILOTE
if ($action === "create" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO user (nom, prenom, email, role, password_hash) VALUES (?, ?, ?, 'Pilote', ?)");
    $stmt->bind_param("ssss", $nom, $prenom, $email, $password);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Pilote créé"]);
}

// MODIFIER UN PILOTE
if ($action === "update" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("UPDATE user SET nom = ?, prenom = ?, email = ? WHERE id = ? AND role = 'Pilote'");
    $stmt->bind_param("sssi", $_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["id"]);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Pilote mis à jour"]);
}

$conn->close();
?>
