<section class="content">
    <h3>Entreprises proposant des stages</h3>
    <?php if (isset($entreprises) && !empty($entreprises)): ?>
        <ul id="entreprises-list">
            <?php foreach ($entreprises as $entreprise): ?>
                <li data-id="<?= htmlspecialchars($entreprise['id']) ?>">
                    <?= htmlspecialchars($entreprise['nom']) ?> - <?= htmlspecialchars($entreprise['ville']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune entreprise trouvée.</p>
    <?php endif; ?>
</section>
