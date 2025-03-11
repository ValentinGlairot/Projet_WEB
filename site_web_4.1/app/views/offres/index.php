<section class="content">
    <h2>Liste des Offres</h2>

    <!-- Formulaire de recherche -->
    <form class="search-form" method="GET" action="<?= BASE_URL ?>index.php">
        <input type="hidden" name="controller" value="offre">
        <input type="hidden" name="action" value="search">

        <label for="motcle">Mot-clé :</label>
        <input type="text" id="motcle" name="motcle" value="<?= isset($motcle) ? htmlspecialchars($motcle) : '' ?>"
            required>

        <button type="submit" class="btn">Rechercher</button>
    </form>

    <div class="offers-container">
        <?php if (!empty($offres)): ?>
            <?php foreach ($offres as $offre): ?>
                <div class='offer-card'>
                    <h4><?= htmlspecialchars($offre['titre']) ?> - <?= htmlspecialchars($offre['entreprise']) ?></h4>
                    <p><strong>Rémunération :</strong> <?= htmlspecialchars($offre['remuneration']) ?>€</p>
                    <p><?= htmlspecialchars($offre['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <div class='offer-buttons'>
                        <!-- Voir l'offre : accessible à tous -->
                        <a href="<?= BASE_URL ?>index.php?controller=offre&action=detail&id=<?= $offre['id'] ?>"
                            class='btn-voir'>Voir</a>

                        <!-- Ajouter à la wishlist : seulement Etudiant -->
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Etudiant'): ?>
                            <form action="<?= BASE_URL ?>index.php?controller=wishlist&action=add" method="POST"
                                style="display:inline;">
                                <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">
                                <button type="submit" class="btn">Ajouter à la Wishlist</button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style='text-align: center; color: #777;'>Aucune offre trouvée.</p>
        <?php endif; ?>
    </div>
</section>