<section class="content">
    <h3>Créer un compte</h3>
    <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form id="register-form" action="<?= BASE_URL ?>index.php?controller=utilisateur&action=register" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="role">Rôle :</label>
        <select id="role" name="role" required>
            <option value="" disabled selected hidden>Sélectionner un rôle</option>
            <option value="Étudiant">Étudiant</option>
            <option value="Admin">Admin</option>
            <option value="pilote">pilote</option>
        </select>


        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required pattern=".{8,}" title="Au moins 8 caractères">

        <label for="confirm-password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm-password" name="confirm-password" required pattern=".{8,}"
            title="Au moins 8 caractères">

        <p id="password-error" class="error-message" style="color: red; display: none;">Les mots de passe ne
            correspondent pas.</p>

        <button type="submit" class="btn-register">S'inscrire</button>
    </form>
    <div class="account-link">
        <p>Vous avez déjà un compte ?</p>
        <a href="<?= BASE_URL ?>index.php?controller=utilisateur&action=connexion" class="btn-login">Se connecter</a>
    </div>
</section>