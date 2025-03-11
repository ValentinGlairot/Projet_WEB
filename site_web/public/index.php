<?php
// public/index.php

ini_set('display_errors', 1);
error_reporting(E_ALL);


// Charger la configuration globale plus les classes
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Récupérer le contrôleur et l'action demandés
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerClass = 'App\\Controller\\' . ucfirst($controllerName) . 'Controller';

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    
    if (method_exists($controller, $actionName)) {
        $reflection = new ReflectionMethod($controller, $actionName);
        
        if ($reflection->getNumberOfRequiredParameters() > 0) {
            if (isset($_GET['id'])) {
                $controller->$actionName($_GET['id']); 
            } else {
                die("Erreur : Paramètre 'id' manquant pour l'action '$actionName'.");
            }
        } else {
            $controller->$actionName();
        }
    } else {
        echo "Action '$actionName' non trouvée dans le contrôleur '$controllerClass'.";
    }
} else {
    echo "Contrôleur '$controllerClass' non trouvé.";
}
?>