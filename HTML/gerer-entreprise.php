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
  <meta name="description" content="Interface de gestion d'entreprises sur CESI Ta Chance">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gérer l'entreprise</title>
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
  <h2>Gérer l'entreprise</h2>
  <p>Dans cet espace, vous pouvez créer, modifier, évaluer ou supprimer une entreprise.</p>

  <div class="dashboard-actions">
    <ul>
      <li><a href="#creer" class="btn-login">Créer</a></li>
      <li><a href="#modifier" class="btn-login">Modifier</a></li>
      <li><a href="#evaluer" class="btn-login">Évaluer</a></li>
      <li><a href="#supprimer" class="btn-login">Supprimer</a></li>
    </ul>
  </div>

  <!-- Créer une entreprise -->
  <div id="creer" style="margin-top: 40px;">
    <h3>Créer une entreprise</h3>
    <p>Remplissez le formulaire pour créer la fiche d’une nouvelle entreprise.</p>
    <form id="create-entreprise-form">
      <label for="nom-creation">Nom :</label>
      <input type="text" id="nom-creation" name="nom" required>

      <label for="desc-creation">Secteur :</label>
      <input type="text" id="desc-creation" name="secteur" required>

      <label for="email-creation">Ville :</label>
      <input type="text" id="email-creation" name="ville" required>

      <label for="tel-creation">Taille :</label>
      <input type="text" id="tel-creation" name="taille" required>

      <button type="submit" class="btn">Créer</button>
    </form>
  </div>

  <!-- Modifier une entreprise -->
  <div id="modifier" style="margin-top: 40px;">
    <h3>Modifier une entreprise</h3>
    <form id="update-entreprise-form">
      <label for="id-modif">ID de l’entreprise :</label>
      <input type="number" id="id-modif" name="id" required>

      <label for="nom-modif">Nouveau nom :</label>
      <input type="text" id="nom-modif" name="nom">

      <label for="desc-modif">Nouveau secteur :</label>
      <input type="text" id="desc-modif" name="secteur">

      <label for="email-modif">Nouvelle ville :</label>
      <input type="text" id="email-modif" name="ville">

      <label for="tel-modif">Nouvelle taille :</label>
      <input type="text" id="tel-modif" name="taille">

      <button type="submit" class="btn">Modifier</button>
    </form>
  </div>

  <!-- Supprimer une entreprise -->
  <div id="supprimer" style="margin-top: 40px;">
    <h3>Supprimer une entreprise</h3>
    <form id="delete-entreprise-form">
      <label for="id-suppr">ID de l’entreprise :</label>
      <input type="number" id="id-suppr" name="id" required>
      <button type="submit" class="btn">Supprimer</button>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>

<script src="../js/gerer-entreprise.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
