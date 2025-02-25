<?php include_once __DIR__ . '/../layout/header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Contrôleur doit déjà filtrer, mais on peut doubler la vérification :
if (!isset($_SESSION['user']) 
    || ( $_SESSION['user']['role'] !== 'Admin'
         && $_SESSION['user']['role'] !== 'pilote' )) {
    echo "<p>Accès refusé.</p>";
    exit;
}

$user = $_SESSION['user'];
?>

<main class="content">
    <h2>Dashboard</h2>
    
    <!-- Message de bienvenue -->
    <?php if (isset($_SESSION['user'])): ?>
        <p class="welcome-message">Bonjour, <?= htmlspecialchars($_SESSION['user']['prenom']) ?> !</p>
    <?php endif; ?>

    <div class="dashboard-actions">
        <ul>
            <!-- Gérer les utilisateurs : Admin seulement -->
            <?php if ($_SESSION['user']['role'] === 'Admin'): ?>
                <li><a href="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=index">Gérer les utilisateurs</a></li>
            <?php endif; ?>

            <!-- Gérer les Offres : Admin/pilote -->
            <?php if ($_SESSION['user']['role'] === 'Admin' || $_SESSION['user']['role'] === 'pilote'): ?>
                <li><a href="<?= BASE_URL ?>index.php?controller=offre&action=gererOffres">Gérer les Offres</a></li>
            <?php endif; ?>

            <!-- Voir les offres de stage : potentiellement tout le monde -->
            <li><a href="<?= BASE_URL ?>index.php?controller=offre&action=index">Voir les offres de stage</a></li>
        </ul>
    </div>
</main>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
