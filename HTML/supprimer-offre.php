<?php
session_start();
require_once '../config/database.php'; // Connexion à la base de données

// Vérification de l'accès (seuls les administrateurs et entreprises peuvent supprimer une offre)
if (!isset($_SESSION["user"]) || ($_SESSION["user"]["role"] !== "admin" && $_SESSION["user"]["role"] !== "entreprise")) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si une offre doit être supprimée
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["offer-id"]) && is_numeric($_POST["offer-id"])) {
        $offer_id = intval($_POST["offer-id"]);

        // Vérifier si l'offre existe
        $stmt_check = $conn->prepare("SELECT id FROM offre WHERE id = ?");
        $stmt_check->bind_param("i", $offer_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $stmt_delete = $conn->prepare("DELETE FROM offre WHERE id = ?");
            $stmt_delete->bind_param("i", $offer_id);

            if ($stmt_delete->execute()) {
                $_SESSION["message"] = "Offre supprimée avec succès !";
            } else {
                $_SESSION["error"] = "Erreur lors de la suppression.";
            }

            $stmt_delete->close();
        } else {
            $_SESSION["error"] = "L'offre n'existe pas.";
        }
        $stmt_check->close();
    } else {
        $_SESSION["error"] = "ID invalide.";
    }

    header("Location: supprimer-offre.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supprimer une Offre</title>
  <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="content">
  <h3>Supprimer une Offre de Stage</h3>

  <?php if (isset($_SESSION["message"])): ?>
      <p class="success-message"><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
  <?php endif; ?>

  <?php if (isset($_SESSION["error"])): ?>
      <p class="error-message"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
  <?php endif; ?>

  <form action="supprimer-offre.php" method="POST">
    <label for="offer-id">ID de l'Offre :</label>
    <input type="text" id="offer-id" name="offer-id" required>
    <button type="submit" class="btn" onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?');">Supprimer l'Offre</button>
  </form>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
