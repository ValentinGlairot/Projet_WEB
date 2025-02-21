<section class="content">
    <h3>Ajouter une Offre de Stage</h3>
    <?php if (isset($_SESSION["message"])): ?>
        <p class="success-message"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["error"])): ?>
        <p class="error-message"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>offres/ajout.php" method="POST">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required>
        
        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="entreprise">Entreprise :</label>
        <input type="text" id="entreprise" name="entreprise" required>
        
        <label for="remuneration">Base de rémunération :</label>
        <input type="text" id="remuneration" name="remuneration" required>
        
        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" required>
        
        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" required>
        
        <button type="submit" class="btn">Publier l'offre</button>
    </form>
</section>
