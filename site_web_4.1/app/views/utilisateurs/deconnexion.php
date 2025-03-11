<?php
session_start();
session_destroy();
header("Location: " . BASE_URL . "utilisateurs/connexion.php");
exit;
