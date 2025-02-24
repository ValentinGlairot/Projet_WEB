<section class="content">
    <h3>Entreprises proposant des stages</h3>

    <?php if (isset($entreprises) && !empty($entreprises)): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entreprises as $entreprise): ?>
                    <tr>
                        <td><?= htmlspecialchars($entreprise['nom']) ?></td>
                        <td><?= htmlspecialchars($entreprise['ville']) ?></td>
                        <td>
                            <!-- Bouton Détails : tout le monde -->
                            <a href="<?= BASE_URL ?>index.php?controller=entreprise&action=details&id=<?= $entreprise['id'] ?>" class="btn-voir">Détails</a>

                            <!-- Boutons Modifier/Supprimer : Admin ou pilote seulement -->
                            <?php if (isset($_SESSION['user']) && 
                                      ( $_SESSION['user']['role'] === 'Admin' 
                                        || $_SESSION['user']['role'] === 'pilote' )): ?>
                                <a href="<?= BASE_URL ?>index.php?controller=entreprise&action=modifier&id=<?= $entreprise['id'] ?>" class="btn-modifier">Modifier</a>

                                <form action="<?= BASE_URL ?>index.php?controller=entreprise&action=supprimer" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $entreprise['id'] ?>">
                                    <button type="submit" class="btn-supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette entreprise ?')">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune entreprise trouvée.</p>
    <?php endif; ?>

    <!-- Formulaire de création de Nouvelle Entreprise
         => on peut l’afficher seulement à Admin/pilote. -->
    <?php if (isset($_SESSION['user']) 
              && ( $_SESSION['user']['role'] === 'Admin' 
                   || $_SESSION['user']['role'] === 'pilote')): ?>
        <h3>Créer une Nouvelle Entreprise</h3>
        <form action="<?= BASE_URL ?>index.php?controller=entreprise&action=creer" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            
            <label for="ville">Ville :</label>
            <input type="text" id="ville" name="ville" required>

            <label for="secteur">Secteur :</label>
            <input type="text" id="secteur" name="secteur" required>

            <label for="taille">Taille :</label>
            <input type="text" id="taille" name="taille" required>

            <!-- Champs description, email, téléphone -->
            <label for="description">Description :</label>
            <textarea id="description" name="description"></textarea>

            <label for="email">Email de contact :</label>
            <input type="email" id="email" name="email">

            <label for="telephone">Téléphone de contact :</label>
            <input type="text" id="telephone" name="telephone">

            <button type="submit" class="btn">Créer</button>
        </form>
    <?php endif; ?>
</section>
