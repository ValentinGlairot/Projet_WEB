<?php
session_start();
?>

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

<script src="../js/header.js"></script>
