<?php include_once __DIR__ . '/../layout/header.php'; ?>

<section class="content">
  <h2>Gérer les Offres</h2>

  <table class="styled-table">
    <thead>
      <tr>
        <th>Titre</th>
        <th>Entreprise</th>
        <th>Rémunération</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($offres as $offre): ?>
        <tr>
          <td><?= htmlspecialchars($offre['titre']) ?></td>
          <td><?= htmlspecialchars($offre['entreprise']) ?></td>
          <td><?= htmlspecialchars($offre['remuneration']) ?>€</td>
          <td><?= htmlspecialchars($offre['date_debut']) ?></td>
          <td><?= htmlspecialchars($offre['date_fin']) ?></td>
          <td>
            <div class='offer-buttons'>
            <a href="<?= BASE_URL ?>index.php?controller=offre&action=modifier&id=<?= $offre['id'] ?>" class="btn-modifier">Modifier</a>
            <a href="<?= BASE_URL ?>index.php?controller=offre&action=supprimer&id=<?= $offre['id'] ?>" class='btn-supprimer'>Supprimer</a>
            </div>

          </td>


        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
