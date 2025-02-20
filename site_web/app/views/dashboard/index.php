<section class="content">
    <h2>Mon Tableau de Bord</h2>
    <div class="dashboard-cards">
        <div class="card">
            <h3>Offres Créées</h3>
            <p><?= htmlspecialchars($offres_creees) ?></p>
        </div>
        <div class="card">
            <h3>Candidatures Reçues</h3>
            <p><?= htmlspecialchars($candidatures_recues) ?></p>
        </div>
        <div class="card">
            <h3>Entreprises Partenaires</h3>
            <p><?= htmlspecialchars($entreprises_partenaire) ?></p>
        </div>
    </div>
    <div class="dashboard-cards">
        <div class="card">
            <h4>Répartition par Compétences</h4>
            <p><?= htmlspecialchars($stats_competences) ?></p>
        </div>
        <div class="card">
            <h4>Durée des Stages</h4>
            <p><?= htmlspecialchars($stats_duree) ?></p>
        </div>
        <div class="card">
            <h4>Top Offres en Wishlist</h4>
            <p><?= htmlspecialchars($stats_wishlist) ?></p>
        </div>
    </div>
</section>
