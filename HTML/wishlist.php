<?php
session_start();
require_once '../config/database.php';

// Vérifie si l'utilisateur est connecté, sinon redirige vers login.php
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION["user"]["id"];

// Récupérer les offres en wishlist de cet utilisateur
$sql = "SELECT w.id AS wishlist_id, o.id AS offre_id, o.titre, e.nom AS entreprise 
        FROM wishlist w
        JOIN offre o ON w.offre_id = o.id
        JOIN entreprise e ON o.entreprise_id = e.id
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma Wishlist</title>
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
  <h3>Offres en wishlist</h3>
  <ul class="wishlist-list">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          <strong><?= htmlspecialchars($row["titre"]) ?></strong> - <?= htmlspecialchars($row["entreprise"]) ?>
          <button class="btn btn-remove" data-wishlist-id="<?= $row['wishlist_id'] ?>">Retirer</button>
        </li>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align: center; color: #777;">Aucune offre en wishlist.</p>
    <?php endif; ?>
  </ul>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/modal.js"></script>
<script src="../js/wishlist.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
