<section class="content">
    <h3>Détails de l'Entreprise</h3>
    <?php if (isset($entreprise)): ?>
        <p><strong>Nom:</strong> <?= htmlspecialchars($entreprise['nom']) ?></p>
        <p><strong>Secteur:</strong> <?= htmlspecialchars($entreprise['secteur']) ?></p>
        <p><strong>Ville:</strong> <?= htmlspecialchars($entreprise['ville']) ?></p>
        <p><strong>Nombre de stagiaires ayant postulé:</strong> <?= htmlspecialchars($entreprise['nb_stagiaires'] ?? 0) ?>
        </p>
        <p><strong>Moyenne des évaluations:</strong> <?= htmlspecialchars($entreprise['moyenne_eval'] ?? 'N/A') ?> / 5</p>
    <?php else: ?>
        <p>Entreprise introuvable.</p>
    <?php endif; ?>
</section>