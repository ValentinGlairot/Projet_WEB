<?php
session_start();
require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>Offre introuvable.</p>";
    exit;
}

$offreId = intval($_GET['id']);
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupération des informations de l'offre
$stmt = $conn->prepare("SELECT o.titre, o.description, o.remuneration, e.nom AS entreprise FROM offre o JOIN entreprise e ON o.entreprise_id = e.id WHERE o.id = ?");
$stmt->bind_param("i", $offreId);
$stmt->execute();
$result = $stmt->get_result();
$offre = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$offre) {
    echo "<p>Offre introuvable.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($offre['titre']) ?></title>
  <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

  <header>
    <img src="../images/logo.png" alt="Logo" class="header-logo">
    <nav>
      <a href="index.php">Accueil</a>
      <a href="offres.php">Offres</a>
      <a href="entreprises.php">Entreprises</a>
      <a href="candidatures.php">Candidatures</a>
      <a href="wishlist.php">Ma Wishlist</a>
      <a href="contact.php">Contact</a>
      <a href="dashboard.php">Dashboard</a>
    </nav>
    <div id="user-menu">
      <?php if (isset($_SESSION['user'])): ?>
        <div id="user-icon">
          <img src="../images/logo_deco.png" alt="Utilisateur" class="user-logo">
          <div class="dropdown-menu">
            <button id="logout-btn">Déconnexion</button>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" id="login-btn" class="btn-login">Connexion</a>
      <?php endif; ?>
    </div>
  </header>

  <section class="content">
    <h3><?= htmlspecialchars($offre['titre']) ?></h3>
    <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre['entreprise']) ?></p>
    <p><strong>Rémunération :</strong> <?= htmlspecialchars($offre['remuneration']) ?> €</p>
    <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
    <button class="btn add-to-wishlist" data-title="<?= htmlspecialchars($offre['titre']) ?>">Ajouter à la wishlist</button>

    <h3>Postuler</h3>
    <form id="postuler-form" method="POST" enctype="multipart/form-data">
      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom" required>

      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required>

      <label for="cv">Joindre votre CV :</label>
      <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>

      <label for="lettre">Lettre de motivation :</label>
      <textarea id="lettre" name="lettre" required></textarea>

      <button type="submit" class="btn">Envoyer ma candidature</button>
    </form>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/offre-detail.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
