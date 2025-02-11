<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Définition des constantes de connexion (si elles ne sont pas définies ailleurs)
define("DB_HOST", "localhost");  // Remplace par ton hôte (ex: localhost)
define("DB_USER", "root");       // Remplace par ton utilisateur MySQL
define("DB_PASS", "");           // Remplace par ton mot de passe MySQL
define("DB_NAME", "projet_web"); // Remplace par ton nom de base de données


// Vérification des valeurs POST
$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs"]);
    exit;
}

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
    exit;
}

// Vérification des identifiants
$stmt = $conn->prepare("SELECT id, nom, prenom, email, role, password FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Nettoie la sortie tampon
$error_output = ob_get_clean();
if (!empty($error_output)) {
    echo json_encode(["success" => false, "message" => "Erreur interne : " . strip_tags($error_output)]);
    exit;
}

if ($user && $password === $user["password"]) { // Comparaison en clair
    $_SESSION["user"] = [
        "id" => $user["id"],
        "nom" => $user["nom"],
        "prenom" => $user["prenom"],
        "email" => $user["email"],
        "role" => $user["role"]
    ];
    echo json_encode(["success" => true, "message" => "Connexion réussie"]);
} else {
    echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
}
?>
