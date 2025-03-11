<?php
// app/controller/MentionsLegalesController.php

namespace App\Controller;

use App\Controller\BaseController;

class MentionsLegalesController extends BaseController
{

    /**
     * Affiche la page des mentions légales.
     */
    public function mentions()
    {
        $this->render('mentions_legales/mentions.php');
    }

    /**
     * Affiche les conditions générales d'utilisation.
     */
    public function conditions()
    {
        $this->render('mentions_legales/conditions.php');
    }

    /**
     * Affiche la politique de confidentialité.
     */
    public function confidentialite()
    {
        $this->render('mentions_legales/confidentialite.php');
    }
}
