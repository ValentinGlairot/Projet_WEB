<?php
header('Content-Type: application/json');

$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "projet_web"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion échouée : " . $conn->connect_error]));
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Requête SQL avec recherche si nécessaire
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT offre.id, offre.titre, offre.remuneration, entreprise.nom AS entreprise 
                            FROM offre 
                            JOIN entreprise ON offre.entreprise_id = entreprise.id
                            WHERE offre.titre LIKE ? 
                            ORDER BY offre.id DESC");
    $searchTerm = "%$search%";
    $stmt->bind_param("s", $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT offre.id, offre.titre, offre.remuneration, entreprise.nom AS entreprise 
                            FROM offre 
                            JOIN entreprise ON offre.entreprise_id = entreprise.id
                            ORDER BY offre.id DESC");
}

$stmt->execute();
$result = $stmt->get_result();

$offres = [];
while ($row = $result->fetch_assoc()) {
    $offres[] = $row;
}

echo json_encode($offres);

$stmt->close();
$conn->close();
?>
