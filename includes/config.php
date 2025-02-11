<?php
$host = 'localhost'; // Adresse du serveur MySQL
$dbname = 'projet_web'; // Nom de la base de données
$username = 'root'; // Ton utilisateur MySQL
$password = ''; // Mets ton mot de passe ici (laisse vide si aucun)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
