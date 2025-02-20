<!-- app/views/wishlist/index.php -->
<section class="content">
    <h3>Ma Wishlist</h3>
    <?php if (!empty($wishlist)): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Entreprise</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wishlist as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['wishlist_id']) ?></td>
                        <td><?= htmlspecialchars($item['titre']) ?></td>
                        <td><?= htmlspecialchars($item['entreprise']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune offre dans la wishlist.</p>
    <?php endif; ?>
</section>
