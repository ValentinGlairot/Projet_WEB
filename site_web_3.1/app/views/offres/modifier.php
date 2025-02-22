<section class="content">
    <h2>Modifier l'Offre</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>index.php?controller=offre&action=modifier&id=<?= $offre['id'] ?>">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($offre['titre']) ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($offre['description']) ?></textarea>

        <label for="remuneration">Rémunération :</label>
        <input type="number" id="remuneration" name="remuneration" value="<?= htmlspecialchars($offre['remuneration']) ?>" required>

        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" value="<?= $offre['date_debut'] ?>" required>

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" value="<?= $offre['date_fin'] ?>" required>

        <!-- Nouveau champ competences -->
        <label for="competences">Compétences :</label>
        <textarea id="competences" name="competences"><?= htmlspecialchars($offre['competences'] ?? '') ?></textarea>

        <button type="submit" class="btn btn-modifier">Modifier l'Offre</button>
    </form>
</section>
