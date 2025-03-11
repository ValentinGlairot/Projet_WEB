<section class="content">
    <h2>Gérer l'Entreprise</h2>
    <p>Dans cet espace, vous pouvez créer, modifier, évaluer ou supprimer une entreprise.</p>
    <div class="dashboard-actions">
        <ul>
            <li><a href="#creer" class="btn-login">Créer</a></li>
            <li><a href="#modifier" class="btn-login">Modifier</a></li>
            <li><a href="#evaluer" class="btn-login">Évaluer</a></li>
            <li><a href="#supprimer" class="btn-login">Supprimer</a></li>
        </ul>
    </div>
    <!-- Formulaire de création d'entreprise -->
    <div id="creer" style="margin-top: 40px;">
        <h3>Créer une Entreprise</h3>
        <form id="create-entreprise-form" action="<?= BASE_URL ?>entreprises/gestion.php?action=create" method="POST">
            <label for="nom-creation">Nom :</label>
            <input type="text" id="nom-creation" name="nom" required>
            <label for="secteur">Secteur :</label>
            <input type="text" id="secteur" name="secteur" required>
            <label for="ville">Ville :</label>
            <input type="text" id="ville" name="ville" required>
            <label for="taille">Taille :</label>
            <input type="text" id="taille" name="taille" required>
            <button type="submit" class="btn">Créer</button>
        </form>
    </div>
    <!-- Formulaire de modification d'entreprise -->
    <div id="modifier" style="margin-top: 40px;">
        <h3>Modifier une Entreprise</h3>
        <form id="update-entreprise-form" action="<?= BASE_URL ?>entreprises/gestion.php?action=update" method="POST">
            <label for="id-modif">ID de l’entreprise :</label>
            <input type="number" id="id-modif" name="id" required>
            <label for="nom-modif">Nouveau nom :</label>
            <input type="text" id="nom-modif" name="nom">
            <label for="secteur-modif">Nouveau secteur :</label>
            <input type="text" id="secteur-modif" name="secteur">
            <label for="ville-modif">Nouvelle ville :</label>
            <input type="text" id="ville-modif" name="ville">
            <label for="taille-modif">Nouvelle taille :</label>
            <input type="text" id="taille-modif" name="taille">
            <button type="submit" class="btn">Modifier</button>
        </form>
    </div>
    <!-- Formulaire de suppression d'entreprise -->
    <div id="supprimer" style="margin-top: 40px;">
        <h3>Supprimer une Entreprise</h3>
        <form id="delete-entreprise-form" action="<?= BASE_URL ?>entreprises/gestion.php?action=delete" method="POST">
            <label for="id-suppr">ID de l’entreprise :</label>
            <input type="number" id="id-suppr" name="id" required>
            <button type="submit" class="btn">Supprimer</button>
        </form>
    </div>
</section>