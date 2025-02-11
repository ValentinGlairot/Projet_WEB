<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; // Mets ton mot de passe si nécessaire
$dbname = "projet_web"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête pour récupérer toutes les entreprises
$sql = "SELECT entreprise.id, entreprise.nom, entreprise.secteur, entreprise.ville, COUNT(candidature.id) AS nb_stagiaires,
               IFNULL(AVG(candidature.statut), 0) AS moyenne_eval
        FROM entreprise
        LEFT JOIN offre ON entreprise.id = offre.entreprise_id
        LEFT JOIN candidature ON offre.id = candidature.offre_id
        GROUP BY entreprise.id, entreprise.nom, entreprise.secteur, entreprise.ville
        ORDER BY entreprise.nom ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Liste et recherche d'entreprises proposant des stages sur CESI Ta Chance">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entreprises</title>
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
  <form class="search-form">
    <input type="text" id="recherche" name="recherche" placeholder="Nom, secteur ou ville">
    <button type="submit" class="btn">Rechercher</button>
  </form>

  <h3 class="section-title">Entreprises proposant des stages</h3>
  <ul id="entreprises-list">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li data-id='{$row['id']}'>
                    {$row['nom']} - {$row['ville']}
                  </li>";
        }
    } else {
        echo "<p style='text-align: center; color: #777;'>Aucune entreprise trouvée.</p>";
    }
    ?>
  </ul>

  <div class="pagination">
    <a href="#">&laquo;</a>
    <a href="#">1</a>
    <a href="#">2</a>
    <a href="#">3</a>
    <a href="#">&raquo;</a>
  </div>

  <!-- Bloc dynamique : recherche et détails d'une entreprise -->
  <section id="entreprise-details" style="margin-top: 30px;">
    <h4>Détails de l'entreprise sélectionnée</h4>
    <p>Nom : <span id="detail-nom">-</span></p>
    <p>Secteur : <span id="detail-secteur">-</span></p>
    <p>Ville : <span id="detail-ville">-</span></p>
    <p>Nombre de stagiaires ayant postulé : <span id="detail-nb-stagiaires">-</span></p>
    <p>Moyenne des évaluations : <span id="detail-moy-eval">-</span> / 5</p>
  </section>

  <div style="text-align: center; margin-top: 30px;">
    <a href="gerer-entreprise.php" class="btn-login">Gérer les entreprises</a>
  </div>
</main>

<?php include '../includes/footer.php'; ?>

<script src="../js/entreprises.js"></script>
<script src="../js/header.js"></script>

</body>
</html>

<?php
$conn->close();
?>
