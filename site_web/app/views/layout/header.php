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
        <a href="<?= BASE_URL ?>index.php?controller=wishlist&action=index">Ma Wishlist</a>
        <a href="<?= BASE_URL ?>index.php?controller=contact&action=index">Contact</a>
        <a href="<?= BASE_URL ?>index.php?controller=dashboard&action=index">Dashboard</a>
    </nav>

    <div id="user-menu">
        <!-- Contenu mis à jour dynamiquement par JavaScript -->
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch('<?= BASE_URL ?>api/check-session.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur de chargement de la session");
            }
            return response.json();
        })
        .then(data => {
            console.log("Données reçues :", data); // ✅ Debug

            let userMenu = document.getElementById("user-menu");

            if (data.loggedIn) {
                console.log("Utilisateur connecté :", data.user);
                userMenu.innerHTML = `
                    <div id="user-icon" class="dropdown">
                    <img src="/site_web/images/logo_deco.png" alt="Déconnexion" class="user-logo">
                    <img src="/images/logo_deco.png" alt="Déconnexion" class="user-logo">
                        <div class="dropdown-menu">
                            <p>Bienvenue, <strong>${data.user.prenom}</strong></p>
                            <form action="<?= BASE_URL ?>index.php?controller=utilisateur&action=logout" method="POST">
                                <button type="submit">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                `;

                document.getElementById("user-icon").addEventListener("click", function() {
                    document.querySelector(".dropdown-menu").classList.toggle("show");
                });
            } else {
                console.log("Utilisateur non connecté.");
                userMenu.innerHTML = `<a href="<?= BASE_URL ?>index.php?controller=utilisateur&action=connexion" id="login-btn" class="btn-login">Connexion</a>`;
            }
        })
        .catch(error => console.error("Erreur lors de la vérification de la session :", error));
});
</script>

</body>
</html>
