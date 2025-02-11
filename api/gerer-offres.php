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

// SUPPRIMER UNE OFFRE
if ($action === "delete" && isset($_GET["id"])) {
    $stmt = $conn->prepare("DELETE FROM offre WHERE id = ?");
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Offre supprimée"]);
    } else {
        echo json_encode(["success" => false, "message" => "Offre introuvable"]);
    }
    $stmt->close();
}

// CRÉER UNE OFFRE
if ($action === "create" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("INSERT INTO offre (titre, entreprise_id, remuneration, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiss", $_POST["titre"], $_POST["entreprise_id"], $_POST["remuneration"], $_POST["date_debut"], $_POST["date_fin"]);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Offre créée"]);
}

// MODIFIER UNE OFFRE
if ($action === "update" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("UPDATE offre SET titre = ?, entreprise_id = ?, remuneration = ?, date_debut = ?, date_fin = ? WHERE id = ?");
    $stmt->bind_param("siissi", $_POST["titre"], $_POST["entreprise_id"], $_POST["remuneration"], $_POST["date_debut"], $_POST["date_fin"], $_POST["id"]);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Offre mise à jour"]);
}

$conn->close();
?>
