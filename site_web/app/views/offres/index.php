<section class="content">
    <h3>Liste des Offres de Stage</h3>
    <?php if (!empty($offres)): ?>
        <div class="offers-container">
            <?php foreach($offres as $offre): ?>
                <div class="offer-card">
                    <h4><?= htmlspecialchars($offre['titre']) ?> - <?= htmlspecialchars($offre['entreprise']) ?></h4>
                    <p><strong>Rémunération :</strong> <?= htmlspecialchars($offre['remuneration']) ?>€</p>
                    <div class="offer-buttons">
                    <a href="<?= BASE_URL ?>index.php?controller=offre&action=detail&id=<?= $offre['id'] ?>" class="btn-voir">Voir</a>
                    <!-- Vous pouvez ajouter ici un bouton pour la wishlist, etc. -->
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune offre disponible.</p>
    <?php endif; ?>
</section>
