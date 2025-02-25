<?php
// Script de mise à jour des mots de passe en clair vers un format sécurisé

try {
    $pdo = new PDO("mysql:host=localhost;dbname=projet_web;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les utilisateurs
    $stmt = $pdo->query("SELECT id, password FROM user");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mise à jour des mots de passe
    foreach ($users as $user) {
        $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);

        // Mise à jour du mot de passe dans la base
        $updateStmt = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashed_password, $user['id']]);

        echo "Mot de passe mis à jour pour l'utilisateur ID: " . $user['id'] . "<br>";
    }

    echo "<br>✅ Tous les mots de passe ont été hachés avec succès.";
} catch (PDOException $e) {
    echo "❌ Erreur lors de la mise à jour : " . $e->getMessage();
}
?>
