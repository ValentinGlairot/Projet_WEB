<section class="hero">
  <h2>Trouve le stage idéal</h2>
  <p>Grâce à nos entreprises partenaires, poste ta candidature en un clic.</p>
</section>

<section class="content">
  <h3>Dernières Offres de Stage</h3>
  <div class="offers-container">
    <?php if (!empty($offres)): ?>
        <?php foreach ($offres as $offre): ?>
            <div class='offer-card'>
                <h4><?= htmlspecialchars($offre['titre']) ?> - <?= htmlspecialchars($offre['entreprise']) ?></h4>
                <div class='offer-buttons'>
                    <a href="<?= BASE_URL ?>index.php?controller=offre&action=detail&id=<?= $offre['id'] ?>" class='btn-voir'>Voir</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style='text-align: center; color: #777;'>Aucune offre disponible pour le moment.</p>
    <?php endif; ?>
  </div>
</section>
