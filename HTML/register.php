<?php
session_start();
require_once '../config/database.php';

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer un compte</title>
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
  <h3>Créer un compte</h3>
  <form id="register-form">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="role">Rôle :</label>
    <select id="role" name="role" required>
      <option value="" disabled selected hidden>Sélectionner un rôle</option>
      <option value="etudiant">Étudiant</option>
      <option value="admin">Administrateur</option>
      <option value="pilote">Pilote</option>
    </select>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required pattern=".{8,}" title="Au moins 8 caractères">

    <label for="confirm-password">Confirmer le mot de passe :</label>
    <input type="password" id="confirm-password" name="confirm-password" required pattern=".{8,}" title="Au moins 8 caractères">

    <p id="password-error" class="error-message" style="color: red; display: none;">Les mots de passe ne correspondent pas.</p>

    <button type="submit" class="btn-register">S'inscrire</button>
  </form>

  <div class="account-link">
    <p>Vous avez déjà un compte ?</p>
    <a href="login.php" class="btn-login">Se connecter</a>
  </div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/register.js"></script>
<script src="../js/header.js"></script>

</body>
</html>
