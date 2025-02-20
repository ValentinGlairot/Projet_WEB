<section class="content">
    <h3>Détail de l'Offre</h3>
    <?php if (isset($offre)): ?>
        <p><strong>Titre:</strong> <?= htmlspecialchars($offre['titre']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
        <p><strong>Durée:</strong> <?= htmlspecialchars($offre['duree']) ?> mois</p>
        <p><strong>Rémunération:</strong> <?= htmlspecialchars($offre['remuneration']) ?>€</p>
        <p><strong>Entreprise:</strong> <?= htmlspecialchars($offre['entreprise']) ?></p>
        <!-- Vous pouvez ajouter ici des boutons (ex : ajouter à la wishlist, postuler, etc.) -->
    <?php else: ?>
        <p>Offre introuvable.</p>
    <?php endif; ?>
</section>
