<section class="content">
    <h3>Supprimer une Offre de Stage</h3>
    <?php if (isset($_SESSION["message"])): ?>
        <p class="success-message"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["error"])): ?>
        <p class="error-message"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>offres/suppression.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette offre ?');">
        <label for="offer-id">ID de l'Offre :</label>
        <input type="text" id="offer-id" name="offer-id" required>
        <button type="submit" class="btn">Supprimer l'Offre</button>
    </form>
</section>
