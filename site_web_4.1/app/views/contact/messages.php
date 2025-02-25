<section class="content">
    <h3>Messages reçus</h3>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= htmlspecialchars($message['nom']) ?></td>
                        <td><?= htmlspecialchars($message['email']) ?></td>
                        <td><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                        <td><?= htmlspecialchars($message['date_envoi']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Aucun message reçu</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
