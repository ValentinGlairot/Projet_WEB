<section class="content">
    <h3>Vos Candidatures</h3>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Entreprise</th>
                <th>Offre</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($candidatures)): ?>
                <?php foreach ($candidatures as $candidature): ?>
                    <?php
                        $statut_class = ($candidature['statut'] == 1) ? "accepted" : "pending";
                        $statut_label = ($candidature['statut'] == 1) ? "Acceptée" : "En attente";
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($candidature['entreprise']) ?></td>
                        <td><?= htmlspecialchars($candidature['titre']) ?></td>
                        <td><?= htmlspecialchars($candidature['date_soumission']) ?></td>
                        <td><span class="status <?= $statut_class ?>"><?= $statut_label ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Aucune candidature en cours</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
