<?php
session_start();
require_once '../config/database.php'; // Connexion à la BDD

// Vérifier si l'utilisateur est connecté (exemple : rôle admin ou entreprise)
if (!isset($_SESSION["user"]) || ($_SESSION["user"]["role"] !== "admin" && $_SESSION["user"]["role"] !== "entreprise")) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $entreprise = trim($_POST["entreprise"]);
    $remuneration = trim($_POST["remuneration"]);
    $date_debut = $_POST["date-debut"];
    $date_fin = $_POST["date-fin"];

    if (!empty($titre) && !empty($description) && !empty($entreprise) && !empty($remuneration) && !empty($date_debut) && !empty($date_fin)) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO offre (titre, description, duree, remuneration, entreprise_id) VALUES (?, ?, DATEDIFF(?, ?), ?, (SELECT id FROM entreprise WHERE nom = ? LIMIT 1))");
        $stmt->bind_param("sssiss", $titre, $description, $date_fin, $date_debut, $remuneration, $entreprise);

        if ($stmt->execute()) {
            $_SESSION["message"] = "Offre ajoutée avec succès !";
            header("Location: offres.php");
            exit;
        } else {
            $_SESSION["error"] = "Erreur lors de l'ajout de l'offre.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $_SESSION["error"] = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter une Offre</title>
  <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="content">
  <h3>Ajouter une Offre de Stage</h3>

  <?php if (isset($_SESSION["message"])): ?>
      <p class="success-message"><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
  <?php endif; ?>

  <?php if (isset($_SESSION["error"])): ?>
      <p class="error-message"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
  <?php endif; ?>

  <form action="ajout-offre.php" method="POST">
    <label for="titre">Titre :</label>
    <input type="text" id="titre" name="titre" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required></textarea>

    <label for="entreprise">Entreprise :</label>
    <input type="text" id="entreprise" name="entreprise" required>

    <label for="remuneration">Base de rémunération :</label>
    <input type="text" id="remuneration" name="remuneration" required>

    <label for="date-debut">Date de début :</label>
    <input type="date" id="date-debut" name="date-debut" required>

    <label for="date-fin">Date de fin :</label>
    <input type="date" id="date-fin" name="date-fin" required>

    <button type="submit" class="btn">Publier l'offre</button>
  </form>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
