<?php
session_start();
require_once '../config/database.php';
header("Content-Type: application/json");

// Vérifier si la requête est POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
    exit;
}

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);
$nom = trim($data["nom"] ?? "");
$prenom = trim($data["prenom"] ?? "");
$email = trim($data["email"] ?? "");
$role = trim($data["role"] ?? "");
$password = $data["password"] ?? "";

// Vérification des champs
if (empty($nom) || empty($prenom) || empty($email) || empty($role) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs"]);
    exit;
}

// Vérifier si l'email est déjà utilisé
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé"]);
    exit;
}
$stmt->close();

// Hasher le mot de passe
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insérer le nouvel utilisateur dans la base de données
$stmt = $conn->prepare("INSERT INTO user (nom, prenom, email, role, password_hash) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nom, $prenom, $email, $role, $hashed_password);
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Inscription réussie"]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'inscription"]);
}

$stmt->close();
$conn->close();
?>
