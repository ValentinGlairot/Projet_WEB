<?php
// public/index.php

// Charger la configuration globale et les classes...
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

$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'offre';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Construit le nom complet de la classe du contrôleur
$controllerClass = 'App\\Controller\\' . ucfirst($controllerName) . 'Controller';

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    if (method_exists($controller, $actionName)) {
        // Utilisation de Reflection pour déterminer le nombre de paramètres requis
        $reflection = new ReflectionMethod($controller, $actionName);
        if ($reflection->getNumberOfRequiredParameters() > 0) {
            if (isset($_GET['id'])) {
                $controller->$actionName($_GET['id']);
            } else {
                echo "Erreur : Paramètre manquant pour l'action '$actionName'.";
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
