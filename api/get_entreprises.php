<?php
header('Content-Type: application/json');

$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "projet_web"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion échouée : " . $conn->connect_error]));
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$sql = "SELECT id, nom, secteur, ville FROM entreprise WHERE nom LIKE ? OR secteur LIKE ? OR ville LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();

$entreprises = [];
while ($row = $result->fetch_assoc()) {
    $entreprises[] = $row;
}

echo json_encode($entreprises);

$stmt->close();
$conn->close();
?>
