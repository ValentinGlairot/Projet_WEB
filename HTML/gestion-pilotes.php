<?php
session_start();
require_once '../config/database.php';

// Vérification de l'authentification (seuls les admins peuvent gérer les pilotes)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$query = "SELECT id, nom, prenom FROM user WHERE role = 'Pilote'";
$result = $conn->query($query);
$pilotes = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Pilotes</title>
  <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<header>
  <img src="../images/logo.png" alt="Logo" class="header-logo">
  <nav>
    <a href="index.php">Accueil</a>
    <a href="offres.php">Offres</a>
    <a href="entreprises.php">Entreprises</a>
    <a href="candidatures.php">Candidatures</a>
    <a href="wishlist.php">Ma Wishlist</a>
    <a href="contact.php">Contact</a>
    <a href="dashboard.php">Dashboard</a>
  </nav>

  <div id="user-menu">
    <?php if (isset($_SESSION['user'])): ?>
      <div id="user-icon">
        <img src="../images/logo_deco.png" alt="Utilisateur" class="user-logo">
        <div class="dropdown-menu">
          <button id="logout-btn">Déconnexion</button>
        </div>
      </div>
    <?php else: ?>
      <a href="login.php" class="btn-login">Connexion</a>
    <?php endif; ?>
  </div>
</header>

<main class="content">
  <h2>Gestion des Pilotes</h2>
  
  <table class="styled-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pilotes as $pilote): ?>
        <tr>
          <td><?= htmlspecialchars($pilote['id']) ?></td>
          <td><?= htmlspecialchars($pilote['nom']) ?></td>
          <td><?= htmlspecialchars($pilote['prenom']) ?></td>
          <td>
            <button class="btn-modifier" data-id="<?= $pilote['id'] ?>">Modifier</button>
            <button class="btn-supprimer" data-id="<?= $pilote['id'] ?>">Supprimer</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3>Ajouter un Pilote</h3>
  <form id="create-pilote-form">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>
    
    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="btn">Créer le Pilote</button>
  </form>
</main>

<?php include '../includes/footer.php'; ?>

<script src="../js/gestion-pilotes.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
