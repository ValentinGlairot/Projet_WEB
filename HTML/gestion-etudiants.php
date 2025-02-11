<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Interface de gestion des étudiants sur CESI Ta Chance">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Étudiants</title>
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
        <a href="login.php" id="login-btn" class="btn-login">Connexion</a>
      <?php endif; ?>
    </div>
</header>

<main class="content">
  <h2>Gestion des Étudiants</h2>

  <div class="dashboard-actions">
    <ul>
      <li><a href="#creer" class="btn-login">Créer un Étudiant</a></li>
      <li><a href="#modifier" class="btn-login">Modifier un Étudiant</a></li>
      <li><a href="#supprimer" class="btn-login">Supprimer un Étudiant</a></li>
      <li><a href="#statistiques" class="btn-login">Statistiques d'un Étudiant</a></li>
    </ul>
  </div>

  <!-- Rechercher un étudiant -->
  <div id="rechercher" style="margin-top: 40px;">
    <h3>Rechercher un Étudiant</h3>
    <form id="search-etudiant-form">
      <label for="email-etudiant">Email :</label>
      <input type="email" id="email-etudiant" name="email" required>
      <button type="submit" class="btn">Rechercher</button>
    </form>

    <div id="result-search" style="margin-top:20px;">
      <h4>Résultat</h4>
      <p>Nom : <span id="res-nom">-</span></p>
      <p>Prénom : <span id="res-prenom">-</span></p>
      <p>Email : <span id="res-email">-</span></p>
    </div>
  </div>

  <!-- Créer un étudiant -->
  <div id="creer" style="margin-top: 40px;">
    <h3>Créer un Étudiant</h3>
    <form id="create-etudiant-form">
      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom" required>
      <label for="prenom">Prénom :</label>
      <input type="text" id="prenom" name="prenom" required>
      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required>
      <button type="submit" class="btn">Créer</button>
    </form>
  </div>

  <!-- Modifier un étudiant -->
  <div id="modifier" style="margin-top: 40px;">
    <h3>Modifier un Étudiant</h3>
    <form id="update-etudiant-form">
      <label for="email-modif">Email :</label>
      <input type="email" id="email-modif" name="email" required>
      <label for="nom-modif">Nouveau Nom :</label>
      <input type="text" id="nom-modif" name="nom">
      <label for="prenom-modif">Nouveau Prénom :</label>
      <input type="text" id="prenom-modif" name="prenom">
      <button type="submit" class="btn">Modifier</button>
    </form>
  </div>

  <!-- Supprimer un étudiant -->
  <div id="supprimer" style="margin-top: 40px;">
    <h3>Supprimer un Étudiant</h3>
    <form id="delete-etudiant-form">
      <label for="email-suppr">Email :</label>
      <input type="email" id="email-suppr" name="email" required>
      <button type="submit" class="btn">Supprimer</button>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../js/gestion-etudiants.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
