<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord</title>
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
    <a href="login.php" id="login-btn" class="btn-login">Connexion</a>
    <div id="user-icon" class="hidden">
      <img src="../images/logo_deco.png" alt="Utilisateur" class="user-logo">
      <div class="dropdown-menu">
        <button id="logout-btn">Déconnexion</button>
      </div>
    </div>
  </div>
</header>


<section class="content">
  <h2>Mon Tableau de Bord</h2>

  <div class="dashboard-cards">
    <div class="card">
      <h3>Offres Créées</h3>
      <p>5</p>
    </div>
    <div class="card">
      <h3>Candidatures Reçues</h3>
      <p>12</p>
    </div>
    <div class="card">
      <h3>Entreprises Partenaires</h3>
      <p>3</p>
    </div>
  </div>

  <div class="dashboard-actions">
    <h4>Actions Rapides</h4>
    <ul>
      <li><a href="gerer-offres.php" class="btn-add">Gérer les offres</a></li>
      <li><a href="gestion-etudiants.php" class="btn-add">Gérer les étudiants</a></li>
      <li><a href="gestion-pilotes.php" class="btn-add">Gérer les pilotes</a></li>
    </ul>
  </div>

  <section class="statistics">
    <h3>Statistiques des Offres</h3>
    <div class="dashboard-cards">
      <div class="card">
        <h4>Répartition par Compétences</h4>
        <p>Web: 40%, Data: 30%, Marketing: 20%, Gestion: 10%</p>
      </div>
      <div class="card">
        <h4>Durée des Stages</h4>
        <p>3 mois: 20%, 6 mois: 50%, 12 mois: 30%</p>
      </div>
      <div class="card">
        <h4>Top Offres en Wishlist</h4>
        <p>Stage Développeur Web</p>
      </div>
    </div>
  </section>
</section>

<?php include '../includes/footer.php'; ?>
<script src="../js/modal.js"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
