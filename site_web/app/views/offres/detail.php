<section class="content">
    <h2>Détail de l'offre</h2>

    <h3>
        <?= isset($offre['titre']) ? htmlspecialchars($offre['titre']) : 'Titre indisponible' ?> -
        <?= isset($offre['entreprise']) ? htmlspecialchars($offre['entreprise']) : 'Entreprise inconnue' ?>
    </h3>

    <p><strong>Rémunération :</strong>
        <?= isset($offre['remuneration']) ? htmlspecialchars($offre['remuneration']) . '€' : 'Non précisée' ?></p>
    <p><strong>Date de début :</strong>
        <?= isset($offre['date_debut']) ? htmlspecialchars($offre['date_debut']) : 'Non précisée' ?></p>
    <p><strong>Date de fin :</strong>
        <?= isset($offre['date_fin']) ? htmlspecialchars($offre['date_fin']) : 'Non précisée' ?></p>
    <p><strong>Description :</strong>
        <?= isset($offre['description']) ? nl2br(htmlspecialchars($offre['description'])) : 'Aucune description' ?></p>

    <div class="offer-buttons">
        <form action="<?= BASE_URL ?>index.php?controller=wishlist&action=add" method="POST" style="display:inline;">
            <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">
            <button type="submit" class="btn">Ajouter à la Wishlist</button>
        </form>
    </div>

    <!-- Formulaire de Candidature -->
    <h3>Postuler</h3>
    <form method="POST" action="<?= BASE_URL ?>index.php?controller=candidature&action=postuler"
        enctype="multipart/form-data">
        <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="cv">Joindre votre CV :</label>
        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>

        <label for="lettre">Lettre de motivation :</label>
        <textarea id="lettre" name="lettre" required></textarea>

        <button type="submit" class="btn">Envoyer ma candidature</button>
    </form>

    <div class="back-button-container">
        <a href="<?= BASE_URL ?>index.php?controller=offre&action=index" class="btn btn-back">⬅ Retour aux offres</a>
    </div>
</section>