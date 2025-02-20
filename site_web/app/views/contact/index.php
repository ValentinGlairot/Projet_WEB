<section class="content">
    <h3>Contactez-nous</h3>
    <form id="contact-form" action="<?= BASE_URL ?>contact/send.php" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        
        <label for="message">Message :</label>
        <textarea id="message" name="message" required></textarea>
        
        <button type="submit" class="btn">Envoyer</button>
    </form>
</section>
