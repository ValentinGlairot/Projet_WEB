<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CESI Ta Chance</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/styles.css">
</head>
<body>

<header>
    <div class="header-logo">
        <?php 
        $isHomePage = isset($_GET['controller']) && $_GET['controller'] == 'home';
        if ($isHomePage): ?>
            <h1>CESI Ta Chance</h1>
        <?php else: ?>
            <img src="<?= BASE_URL ?>images/logo.png" alt="Logo">
        <?php endif; ?>
    </div>

    <nav>
        <a href="<?= BASE_URL ?>index.php?controller=home&action=index">Accueil</a>
        <a href="<?= BASE_URL ?>index.php?controller=offre&action=index">Offres</a>
        <a href="<?= BASE_URL ?>index.php?controller=entreprise&action=index">Entreprises</a>
        <a href="<?= BASE_URL ?>index.php?controller=candidature&action=index">Candidatures</a>

        <!-- Wishlist : désormais visible pour Admin OU Etudiant -->
        <?php if (isset($_SESSION['user']) && (
             $_SESSION['user']['role'] === 'Étudiant' 
             || $_SESSION['user']['role'] === 'Admin'
        )): ?>
            <a href="<?= BASE_URL ?>index.php?controller=wishlist&action=index">Ma Wishlist</a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>index.php?controller=contact&action=index">Contact</a>

        <!-- Dashboard : Admin ou pilote -->
        <?php if (isset($_SESSION['user']) && 
                  ( $_SESSION['user']['role'] === 'Admin' 
                    || $_SESSION['user']['role'] === 'pilote' ) ): ?>
            <a href="<?= BASE_URL ?>index.php?controller=dashboard&action=index">Dashboard</a>
        <?php endif; ?>
    </nav>

    <div id="user-menu">
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Icône utilisateur si connecté -->
            <div id="user-icon" class="dropdown">
                <img src="<?= BASE_URL ?>images/logo_deco.png" alt="Déconnexion" class="user-logo">
                <div class="dropdown-menu">
                    <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user']['prenom']) ?></strong></p>
                    <form action="<?= BASE_URL ?>index.php?controller=utilisateur&action=logout" method="POST">
                        <button type="submit">Déconnexion</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Bouton Connexion si l'utilisateur n'est pas connecté -->
            <a href="<?= BASE_URL ?>index.php?controller=utilisateur&action=connexion" id="login-btn" class="btn-login">Connexion</a>
        <?php endif; ?>
    </div>


</header>

<!-- Script pour gérer le menu utilisateur -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let userIcon = document.getElementById("user-icon");
    let dropdownMenu = document.querySelector(".dropdown-menu");
    
    if (userIcon && dropdownMenu) {
        userIcon.addEventListener("click", function() {
            dropdownMenu.classList.toggle("show");
        });
    }
});
</script>
</body>
</html>
