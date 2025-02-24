<?php include_once __DIR__ . '/../layout/header.php'; ?>

<main class="content">
    <h2>Ma Wishlist</h2>

    <?php if (!empty($wishlist)) : ?>
        <ul>
            <?php foreach ($wishlist as $item) : ?>
                <li>
                    <strong><?= htmlspecialchars($item['titre']) ?></strong> - <?= htmlspecialchars($item['entreprise']) ?>
                    <form action="<?= BASE_URL ?>index.php?controller=wishlist&action=remove" method="POST">
                        <input type="hidden" name="wishlist_id" value="<?= $item['wishlist_id'] ?>">
                        <button type="submit" class="btn">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Votre wishlist est vide.</p>
    <?php endif; ?>
</main>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
