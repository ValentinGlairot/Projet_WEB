<?php
session_start();
$host = "localhost";
$user = "root";
$pass = ""; // Mettre ton mot de passe si nécessaire
$dbname = "projet_web";

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password_saisi = $_POST['password'];

    // Requête pour récupérer l'utilisateur
    $sql = "SELECT id, email, password FROM utilisateur WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Comparaison du mot de passe en clair
        if ($password_saisi === $user['password']) {
            $_SESSION['user'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Aucun compte trouvé avec cet email.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
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
  <h3>Connexion</h3>
  <?php if (isset($error)): ?>
    <p style="color: red; text-align: center;"><?php echo $error; ?></p>
  <?php endif; ?>
  <form id="login-form" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required pattern=".{8,}" title="Au moins 8 caractères">

    <button type="submit" class="btn">Se connecter</button>
  </form>

  <div class="account-link">
    <p>Pas encore de compte ?</p>
    <a href="register.php" class="btn-add">Créer un compte</a>
  </div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/modal.js"></script>
<script src="../js/login.js"></script>
<script src="../js/header.js"></script>
</body>
</html>
