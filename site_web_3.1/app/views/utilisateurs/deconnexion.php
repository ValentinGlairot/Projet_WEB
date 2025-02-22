<?php
// Ce fichier n'est pas destiné à être affiché directement.
// La déconnexion est gérée via le contrôleur et ce fichier redirige vers la page de connexion.
session_start();
session_destroy();
header("Location: " . BASE_URL . "utilisateurs/connexion.php");
exit;
