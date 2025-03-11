<?php
// app/model/Candidature.php

namespace App\Model;

class Candidature extends BaseModel
{
    public $id;
    public $user_id;
    public $offre_id;
    public $date_soumission;
    public $statut;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Récupère toutes les candidatures pour un utilisateur donné.
     *
     * @param int $user_id
     * @return array
     */
    public static function findByUserId($user_id)
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM candidature WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde la candidature (insertion ou mise à jour).
     *
     * @return bool
     */
    public function save()
    {
        if (isset($this->id)) {
            // Mise à jour
            $stmt = $this->pdo->prepare("UPDATE candidature SET user_id = ?, offre_id = ?, date_soumission = ?, statut = ? WHERE id = ?");
            return $stmt->execute([$this->user_id, $this->offre_id, $this->date_soumission, $this->statut, $this->id]);
        } else {
            // Insertion
            $stmt = $this->pdo->prepare("INSERT INTO candidature (user_id, offre_id, date_soumission, statut) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$this->user_id, $this->offre_id, $this->date_soumission, $this->statut]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $result;
        }
    }
}
?>