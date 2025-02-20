<section class="content">
    <h3>Connexion</h3>
    <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form id="login-form" action="<?= BASE_URL ?>utilisateurs/connexion.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required pattern=".{8,}" title="Au moins 8 caractères">
        
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <div class="account-link">
        <p>Pas encore de compte ?</p>
        <a href="<?= BASE_URL ?>utilisateurs/inscription.php" class="btn-add">Créer un compte</a>
    </div>
</section>
