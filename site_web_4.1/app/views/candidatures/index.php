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
        <?php
            // Pour simplifier, on récupère le rôle (si la session est active).
            // Autrement, vous pouvez utiliser $_SESSION['user']['role'] directement.
            $userRole = $_SESSION['user']['role'] ?? null;
        ?>
        <?php foreach ($candidatures as $candidature): ?>
            <?php
                // 0 => "En attente", 1 => "Acceptée", 2 => "Refusée"
                $statut_class = match($candidature['statut']) {
                    1 => "accepted",
                    2 => "refused",
                    default => "pending"
                };
                $statut_label = match($candidature['statut']) {
                    1 => "Acceptée",
                    2 => "Refusée",
                    default => "En attente"
                };
            ?>
            <tr>
                <td><?= htmlspecialchars($candidature['entreprise']) ?></td>
                <td><?= htmlspecialchars($candidature['titre']) ?></td>
                <td><?= htmlspecialchars($candidature['date_soumission']) ?></td>
                
                <td>
                    <!-- Affichage simple du statut en texte -->
                    <span class="status <?= $statut_class ?>"><?= $statut_label ?></span>

                    <!-- Si ADMIN ou pilote : on affiche un mini-form pour changer le statut -->
                    <?php if (in_array($userRole, ['Admin', 'pilote'])): ?>
                        <form action="<?= BASE_URL ?>index.php?controller=candidature&action=updateStatus" 
                              method="post" style="display:inline;">
                            <input type="hidden" name="candidature_id" value="<?= $candidature['id'] ?>">

                            <select name="statut">
                                <option value="0" <?= $candidature['statut'] == 0 ? 'selected' : '' ?>>En attente</option>
                                <option value="1" <?= $candidature['statut'] == 1 ? 'selected' : '' ?>>Acceptée</option>
                                <option value="2" <?= $candidature['statut'] == 2 ? 'selected' : '' ?>>Refusée</option>
                            </select>

                            <button type="submit" class="btn">Modifier</button>
                        </form>
                    <?php endif; ?>
                </td>
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
