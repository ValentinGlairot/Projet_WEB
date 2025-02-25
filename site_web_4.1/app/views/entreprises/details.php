<<section class="content">
    <h3>Détails de l'Entreprise</h3>
    <?php if (isset($entreprise)): ?>
        <p><strong>Nom:</strong> <?= htmlspecialchars($entreprise['nom']) ?></p>
        <p><strong>Secteur:</strong> <?= htmlspecialchars($entreprise['secteur']) ?></p>
        <p><strong>Ville:</strong> <?= htmlspecialchars($entreprise['ville']) ?></p>

        <!-- Nouveaux champs -->
        <?php if (!empty($entreprise['description'])): ?>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($entreprise['description'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($entreprise['email'])): ?>
            <p><strong>Email de contact:</strong> <?= htmlspecialchars($entreprise['email']) ?></p>
        <?php endif; ?>
        <?php if (!empty($entreprise['telephone'])): ?>
            <p><strong>Téléphone:</strong> <?= htmlspecialchars($entreprise['telephone']) ?></p>
        <?php endif; ?>

        <p><strong>Nombre de stagiaires ayant postulé:</strong> <?= htmlspecialchars($entreprise['nb_stagiaires'] ?? 0) ?></p>
        <?php 
          $moy = $entreprise['moyenne_eval'] ?? null;
          $moyenneAffiche = ($moy ? round($moy, 2) : 'N/A');
        ?>
        <p><strong>Moyenne des évaluations:</strong> <?= $moyenneAffiche ?> / 5</p>
    <?php else: ?>
        <p>Entreprise introuvable.</p>
    <?php endif; ?>

    <!-- Bouton pour évaluer -->
    <h3>Évaluer cette entreprise</h3>
    <form action="<?= BASE_URL ?>index.php?controller=entreprise&action=evaluer&id=<?= $entreprise['id'] ?>" method="POST">
        <label for="note">Note (1 à 5) :</label>
        <input type="number" id="note" name="note" min="1" max="5" required>
        <br>
        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire"></textarea>
        <br>
        <button type="submit" class="btn">Envoyer l'évaluation</button>
    </form>
</section>
