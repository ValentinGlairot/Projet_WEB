<?php include_once __DIR__ . '/../layout/header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    // Redirection si l'utilisateur n'est pas connecté
    header("Location: " . BASE_URL . "index.php?controller=utilisateur&action=connexion");
    exit;
}

$user = $_SESSION['user'];
?>

<main class="content">
    <h2>Dashboard</h2>
    
    <!-- Ajout du message de bienvenue -->
    <?php if (isset($_SESSION['user'])): ?>
        <p class="welcome-message">Bonjour, <?= htmlspecialchars($_SESSION['user']['prenom']) ?> !</p>
    <?php endif; ?>

    <div class="dashboard-actions">
        <ul>
            <li><a href="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=index">Gérer les utilisateurs</a></li>
            <li><a href="<?= BASE_URL ?>index.php?controller=offre&action=gererOffres">Gérer les Offres</a></li>
            <li><a href="<?= BASE_URL ?>index.php?controller=offre&action=index">Voir les offres de stage</a></li>
        </ul>
    </div>
</main>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
