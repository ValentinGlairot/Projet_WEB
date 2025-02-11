<?php
session_start();
require_once '../config/database.php'; // Connexion à la BDD

// Vérifier si l'utilisateur est connecté (exemple : rôle admin ou entreprise)
if (!isset($_SESSION["user"]) || ($_SESSION["user"]["role"] !== "admin" && $_SESSION["user"]["role"] !== "entreprise")) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Si une offre est demandée
$offre = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $offre_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM offre WHERE id = ?");
    $stmt->bind_param("i", $offre_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $offre = $result->fetch_assoc();
    $stmt->close();
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $offre_id = $_POST["offer-id"];
    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $entreprise = trim($_POST["entreprise"]);
    $remuneration = trim($_POST["remuneration"]);
    $date_debut = $_POST["date-debut"];
    $date_fin = $_POST["date-fin"];

    if (!empty($titre) && !empty($description) && !empty($entreprise) && !empty($remuneration) && !empty($date_debut) && !empty($date_fin)) {
        $stmt = $conn->prepare("UPDATE offre SET titre = ?, description = ?, duree = DATEDIFF(?, ?), remuneration = ?, entreprise_id = (SELECT id FROM entreprise WHERE nom = ? LIMIT 1) WHERE id = ?");
        $stmt->bind_param("sssisi", $titre, $description, $date_fin, $date_debut, $remuneration, $entreprise, $offre_id);

        if ($stmt->execute()) {
            $_SESSION["message"] = "Offre modifiée avec succès !";
            header("Location: offres.php");
            exit;
        } else {
            $_SESSION["error"] = "Erreur lors de la modification de l'offre.";
        }

        $stmt->close();
    } else {
        $_SESSION["error"] = "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier une Offre</title>
  <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="content">
  <h3>Modifier une Offre de Stage</h3>

  <?php if (isset($_SESSION["message"])): ?>
      <p class="success-message"><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
  <?php endif; ?>

  <?php if (isset($_SESSION["error"])): ?>
      <p class="error-message"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
  <?php endif; ?>

  <?php if ($offre): ?>
  <form action="modifier-offre.php?id=<?php echo $offre["id"]; ?>" method="POST">
    <input type="hidden" name="offer-id" value="<?php echo $offre["id"]; ?>">

    <label for="titre">Titre :</label>
    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($offre["titre"]); ?>" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($offre["description"]); ?></textarea>

    <label for="entreprise">Entreprise :</label>
    <input type="text" id="entreprise" name="entreprise" value="<?php echo htmlspecialchars($offre["entreprise_id"]); ?>" required>

    <label for="remuneration">Base de rémunération :</label>
    <input type="text" id="remuneration" name="remuneration" value="<?php echo htmlspecialchars($offre["remuneration"]); ?>" required>

    <label for="date-debut">Date de début :</label>
    <input type="date" id="date-debut" name="date-debut" value="<?php echo htmlspecialchars($offre["date_debut"]); ?>" required>

    <label for="date-fin">Date de fin :</label>
    <input type="date" id="date-fin" name="date-fin" value="<?php echo htmlspecialchars($offre["date_fin"]); ?>" required>

    <button type="submit" class="btn">Modifier l'Offre</button>
  </form>
  <?php else: ?>
      <p class="error-message">Offre introuvable.</p>
  <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
