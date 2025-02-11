<?php
session_start();
require_once '../config/database.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "Veuillez vous connecter pour postuler."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
    exit;
}

$userId = $_SESSION['user']['id'];
$offreId = intval($_POST['offre_id'] ?? 0);
$dateSoumission = date('Y-m-d');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données."]);
    exit;
}

// Vérifier si l'utilisateur a déjà postulé
$stmt = $conn->prepare("SELECT id FROM candidature WHERE user_id = ? AND offre_id = ?");
$stmt->bind_param("ii", $userId, $offreId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Vous avez déjà postulé à cette offre."]);
    exit;
}
$stmt->close();

// Insérer la candidature
$stmt = $conn->prepare("INSERT INTO candidature (statut, date_soumission, user_id, offre_id) VALUES (0, ?, ?, ?)");
$stmt->bind_param("sii", $dateSoumission, $userId, $offreId);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Candidature envoyée avec succès !"]);
} else {
    echo json_encode(["success" => false, "message" => "Une erreur s'est produite lors de l'envoi de la candidature."]);
}
?>
