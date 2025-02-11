<?php
$host = "localhost";
$user = "root";
$pass = ""; // Mettre ton mot de passe si nécessaire
$dbname = "projet_web"; // ✅ Vérifie bien que c'est écrit sans espace

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête pour récupérer les offres
$sql = "SELECT offre.id, offre.titre, entreprise.nom AS entreprise
        FROM offre
        JOIN entreprise ON offre.entreprise_id = entreprise.id
        ORDER BY offre.id DESC LIMIT 5"; // Afficher les 5 dernières offres

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CESI Ta Chance - Accueil</title>
  <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="hero">
  <h2>Trouve le stage idéal</h2>
  <p>Grâce à nos entreprises partenaires, poste ta candidature en un clic.</p>
</section>

<section class="content">
  <h3>Dernières Offres de Stage</h3>
  <div class="offers-container">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='offer-card'>
                    <h4>{$row['titre']} - {$row['entreprise']}</h4>
                    <div class='offer-buttons'>
                        <a href='offre-detail.php?id={$row['id']}' class='btn-voir'>Voir</a>
                    </div>
                  </div>";
        }
    } else {
        echo "<p style='text-align: center; color: #777;'>Aucune offre disponible pour le moment.</p>";
    }
    ?>
  </div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/header.js"></script>

</body>
</html>

<?php
$conn->close();
?>
