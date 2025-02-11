<?php
session_start();
require_once '../config/database.php';

header("Content-Type: application/json");

if (!isset($_SESSION["user"])) {
    echo json_encode(["success" => false, "message" => "Utilisateur non connecté."]);
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion : " . $conn->connect_error]);
    exit();
}

// Récupérer les données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data["wishlist_id"])) {
    echo json_encode(["success" => false, "message" => "ID de la wishlist manquant."]);
    exit();
}

$wishlist_id = intval($data["wishlist_id"]);

// Supprimer l'offre de la wishlist
$sql = "DELETE FROM wishlist WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wishlist_id);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Impossible de supprimer l'offre."]);
}
?>
