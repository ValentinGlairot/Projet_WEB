<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; // Met ton mot de passe si nécessaire
$dbname = "projet_web"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête pour récupérer toutes les offres
$sql = "SELECT offre.id, offre.titre, offre.remuneration, entreprise.nom AS entreprise 
        FROM offre
        JOIN entreprise ON offre.entreprise_id = entreprise.id
        ORDER BY offre.id DESC"; // Trie par les plus récentes

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Offres de Stage sur CESI Ta Chance">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Offres de Stage</title>
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
    <a href="login.php" id="login-btn" class="btn-login">Connexion</a>
    <div id="user-icon" class="hidden">
      <img src="../images/logo_deco.png" alt="Utilisateur" class="user-logo">
      <div class="dropdown-menu">
        <button id="logout-btn">Déconnexion</button>
      </div>
    </div>
  </div>
</header>

<main class="content">
  <h3>Recherche d'offres</h3>
  <form class="search-form">
    <label for="motcle">Mot-clé :</label>
    <input type="text" id="motcle" name="motcle">
    <button type="submit" class="btn">Rechercher</button>
  </form>

  <h3 class="spaced-title">Liste des offres disponibles</h3>
  <div class="offers-container">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<div class='offer-card'>
        <h4>" . $row['titre'] . " - " . $row['entreprise'] . "</h4>
          <p><strong>Rémunération :</strong> " . $row['remuneration'] . "€</p>
          <div class='offer-buttons'>
              <a href='offre-detail.php?id=" . $row['id'] . "' class='btn-voir'>Voir</a>
              <button class='btn btn-add-wishlist' data-title='" . htmlspecialchars($row['titre']) . "' data-id='" . $row['id'] . "'>Ajouter à la wishlist</button>
          </div>
        </div>";
        }
    } else {
        echo "<p style='text-align: center; color: #777;'>Aucune offre disponible pour le moment.</p>";
    }
    ?>
  </div>
</main>

<?php include '../includes/footer.php'; ?>

<script src="../js/offres.js"></script>
<script src="../js/header.js"></script>

</body>
</html>

<?php
$conn->close();
?>
s