<?php
// app/controller/BaseController.php

namespace App\Controller;

class BaseController {

    /**
     * Méthode de rendu de la vue.
     *
     * @param string $view   Chemin relatif depuis le dossier views
     * @param array  $params Variables à extraire pour la vue
     */
    protected function render($view, $params = []) {
        // Extraire les variables pour la vue
        extract($params);

        // dirname(__DIR__) remonte au dossier "app"
        require_once dirname(__DIR__) . '/views/layout/header.php';
        require_once dirname(__DIR__) . '/views/' . $view;
        require_once dirname(__DIR__) . '/views/layout/footer.php';
    }
}
