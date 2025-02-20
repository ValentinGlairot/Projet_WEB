<section class="content">
    <h3>Modifier une Offre de Stage</h3>
    <?php if (isset($_SESSION["message"])): ?>
        <p class="success-message"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["error"])): ?>
        <p class="error-message"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <?php if (isset($offre)): ?>
    <form action="<?= BASE_URL ?>offres/modification.php?id=<?= $offre['id'] ?>" method="POST">
        <input type="hidden" name="offer-id" value="<?= $offre['id'] ?>">
        
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($offre['titre']) ?>" required>
        
        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($offre['description']) ?></textarea>
        
        <label for="entreprise">Entreprise :</label>
        <input type="text" id="entreprise" name="entreprise" value="<?= htmlspecialchars($offre['entreprise_id']) ?>" required>
        
        <label for="remuneration">Base de rémunération :</label>
        <input type="text" id="remuneration" name="remuneration" value="<?= htmlspecialchars($offre['remuneration']) ?>" required>
        
        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($offre['date_debut']) ?>" required>
        
        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" value="<?= htmlspecialchars($offre['date_fin']) ?>" required>
        
        <button type="submit" class="btn">Modifier l'Offre</button>
    </form>
    <?php else: ?>
        <p>Offre introuvable.</p>
    <?php endif; ?>
</section>
