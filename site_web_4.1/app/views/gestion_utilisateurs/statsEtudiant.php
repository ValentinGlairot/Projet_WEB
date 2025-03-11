<section class="content">
    <h2>Statistiques Étudiant</h2>

    <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($etudiant['email']) ?></p>

    <hr>

    <p><strong>Nombre de candidatures envoyées :</strong> <?= $nbCandidatures ?></p>
    <p><strong>Nombre d'offres en wishlist :</strong> <?= $nbWishlist ?></p>

    <!-- Rajouter plus de statistiques si besoin (candidatures acceptées, etc.). -->

    <div style="margin-top:20px;">
        <a class="btn" href="<?= BASE_URL ?>index.php?controller=gestionutilisateurs&action=index">Retour à la liste des
            utilisateurs</a>
    </div>
</section>