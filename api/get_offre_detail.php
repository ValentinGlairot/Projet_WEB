<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT o.id, o.titre, o.description, o.duree, o.remuneration, e.nom AS entreprise 
            FROM offre o
            JOIN entreprise e ON o.entreprise_id = e.id
            WHERE o.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($offre);
} else {
    echo json_encode(["error" => "ID non fourni"]);
}
?>
