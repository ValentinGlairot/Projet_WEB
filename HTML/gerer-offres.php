<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "projet_web";

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupération de l'utilisateur
$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];

// Récupérer les offres (toutes si admin, sinon celles de l'utilisateur)
if ($user_role === "admin") {
    $sql = "SELECT o.id, e.nom AS entreprise, o.titre, o.description, o.date_debut, o.date_fin, o.remuneration
            FROM offre o
            JOIN entreprise e ON o.entreprise_id = e.id
            ORDER BY o.date_debut DESC";
} else {
    $sql = "SELECT o.id, e.nom AS entreprise, o.titre, o.description, o.date_debut, o.date_fin, o.remuneration
            FROM offre o
            JOIN entreprise e ON o.entreprise_id = e.id
            WHERE e.id = ?
            ORDER BY o.date_debut DESC";
}

$stmt = $conn->prepare($sql);

if ($user_role !== "admin") {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gérer les Offres</title>
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
  <h3>Gérer les Offres</h3>
  <table class="styled-table">
    <thead>
      <tr>
        <th>Entreprise</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Date de Début</th>
        <th>Date de Fin</th>
        <th>Rémunération</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['entreprise']}</td>
                      <td>{$row['titre']}</td>
                      <td>{$row['description']}</td>
                      <td>{$row['date_debut']}</td>
                      <td>{$row['date_fin']}</td>
                      <td>{$row['remuneration']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='6' style='text-align:center;'>Aucune offre disponible</td></tr>";
      }
      ?>
    </tbody>
  </table>
</section>

<script src="../js/header.js"></script>
<?php include '../includes/footer.php'; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
