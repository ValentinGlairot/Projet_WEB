<?php
session_start();
require_once '../config/database.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
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

<section class="content">
  <h3>Contactez-nous</h3>
  <form id="contact-form">
  <label for="nom">Nom :</label>
  <input type="text" id="nom" name="nom" required>

  <label for="email">Email :</label>
  <input type="email" id="email" name="email" required>

  <label for="message">Message :</label>
  <textarea id="message" name="message" required></textarea>

  <button type="submit" class="btn">Envoyer</button>
</form>

<p id="form-message" style="display: none; color: green;"></p>

</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/modal.js"></script>
<script src="../js/contact.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
