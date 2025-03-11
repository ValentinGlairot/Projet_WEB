<?php session_start(); ?>
<section class="content">
    <h2>Créer une nouvelle offre</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>index.php?controller=offre&action=create">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>

        <label for="entreprise_id">ID de l'Entreprise :</label>
        <input type="number" id="entreprise_id" name="entreprise_id" required>

        <label for="remuneration">Rémunération :</label>
        <input type="text" id="remuneration" name="remuneration" required>

        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" required>

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" required>

        <button type="submit" class="btn">Créer l'offre</button>
    </form>
</section>