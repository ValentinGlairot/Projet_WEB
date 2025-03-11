<?php if (isset($_SESSION["success"])): ?>
    <p class="success-message"><?= $_SESSION["success"]; ?></p>
    <?php unset($_SESSION["success"]); ?>
<?php endif; ?>

<?php if (isset($_SESSION["error"])): ?>
    <p class="error-message"><?= $_SESSION["error"]; ?></p>
    <?php unset($_SESSION["error"]); ?>
<?php endif; ?>

<section class="content">
    <h3>Contactez-nous</h3>
    <form id="contact-form" action="<?= BASE_URL ?>index.php?controller=contact&action=send" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message :</label>
        <textarea id="message" name="message" required></textarea>

        <button type="submit" class="btn">Envoyer</button>
    </form>
</section>