<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "projet_web";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion échouée : " . $conn->connect_error]));
}

$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];

if ($user_role === "admin") {
    $sql = "SELECT candidature.id, entreprise.nom AS entreprise, offre.titre, candidature.date_soumission, candidature.statut
            FROM candidature
            INNER JOIN offre ON candidature.offre_id = offre.id
            INNER JOIN entreprise ON offre.entreprise_id = entreprise.id
            ORDER BY candidature.date_soumission DESC";
} else {
    $sql = "SELECT candidature.id, entreprise.nom AS entreprise, offre.titre, candidature.date_soumission, candidature.statut
            FROM candidature
            INNER JOIN offre ON candidature.offre_id = offre.id
            INNER JOIN entreprise ON offre.entreprise_id = entreprise.id
            WHERE candidature.user_id = ?
            ORDER BY candidature.date_soumission DESC";
}

$stmt = $conn->prepare($sql);

if ($user_role !== "admin") {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$candidatures = [];
while ($row = $result->fetch_assoc()) {
    $candidatures[] = $row;
}

echo json_encode($candidatures);

$stmt->close();
$conn->close();
?>
