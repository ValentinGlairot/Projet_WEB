<?php
// app/views/offres/detail.php

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer le rôle de l'utilisateur connecté (ou null s'il n'est pas connecté)
$userRole = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;
?>

<section class="content">
    <h2>Détail de l'offre</h2>

    <h3>
        <?= isset($offre['titre']) ? htmlspecialchars($offre['titre']) : 'Titre indisponible' ?> - 
        <?= isset($offre['entreprise']) ? htmlspecialchars($offre['entreprise']) : 'Entreprise inconnue' ?>
    </h3>

    <p><strong>Rémunération :</strong> <?= isset($offre['remuneration']) ? htmlspecialchars($offre['remuneration']) . '€' : 'Non précisée' ?></p>
    <p><strong>Date de début :</strong> <?= isset($offre['date_debut']) ? htmlspecialchars($offre['date_debut']) : 'Non précisée' ?></p>
    <p><strong>Date de fin :</strong> <?= isset($offre['date_fin']) ? htmlspecialchars($offre['date_fin']) : 'Non précisée' ?></p>
    <p><strong>Description :</strong> <?= isset($offre['description']) ? nl2br(htmlspecialchars($offre['description'])) : 'Aucune description' ?></p>

    <div class="offer-buttons">
        <!-- Bouton Wishlist visible uniquement pour Étudiant -->
        <?php if ($userRole === 'Étudiant'): ?>
            <form action="<?= BASE_URL ?>index.php?controller=wishlist&action=add" method="POST" style="display:inline;">
                <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">
                <button type="submit" class="btn btn-wishlist">❤️ Ajouter à la Wishlist</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Formulaire de candidature (postuler) pour les étudiants -->
    <?php if ($userRole === 'Étudiant'): ?>
        <h3>Postuler à cette offre</h3>
        <form action="<?= BASE_URL ?>index.php?controller=candidature&action=postuler" method="post" enctype="multipart/form-data">
            <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">
            
            <label for="cv">CV (PDF uniquement) :</label>
            <input type="file" id="cv" name="cv" accept="application/pdf" required>
            
            <label for="lettre_motivation">Lettre de motivation :</label>
            <textarea id="lettre_motivation" name="lettre_motivation" required></textarea>
            
            <button type="submit" class="btn">📩 Postuler</button>
        </form>
    <?php else: ?>
        <p>Seuls les étudiants peuvent postuler à une offre.</p>
    <?php endif; ?>

    <div class="back-button-container">
        <a href="<?= BASE_URL ?>index.php?controller=offre&action=detail&id=<?= $offre['id'] ?>" class="btn btn-back">⬅ Retour aux offres</a>
    </div>
</section>
