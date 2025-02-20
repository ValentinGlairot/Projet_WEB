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
    <img src="<?= BASE_URL ?>images/logo.png" alt="Logo" class="header-logo">
    <nav>
        <a href="<?= BASE_URL ?>index.php?controller=offre&action=index">Accueil</a>
        <a href="<?= BASE_URL ?>index.php?controller=offre&action=index">Offres</a>
        <a href="<?= BASE_URL ?>index.php?controller=entreprise&action=index">Entreprises</a>
        <a href="<?= BASE_URL ?>index.php?controller=candidature&action=index">Candidatures</a>
        <a href="<?= BASE_URL ?>index.php?controller=wishlist&action=index">Ma Wishlist</a>
        <a href="<?= BASE_URL ?>index.php?controller=contact&action=index">Contact</a>
        <a href="<?= BASE_URL ?>index.php?controller=dashboard&action=index">Dashboard</a>
    </nav>
    <div id="user-menu">
        <?php if (isset($_SESSION['user'])): ?>
            <div id="user-icon">
                <img src="<?= BASE_URL ?>images/logo_deco.png" alt="Utilisateur" class="user-logo">
                <div class="dropdown-menu">
                    <a href="<?= BASE_URL ?>index.php?controller=utilisateur&action=logout">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <a href="<?= BASE_URL ?>index.php?controller=utilisateur&action=connexion" class="btn-login">Connexion</a>
        <?php endif; ?>
    </div>
</header>
