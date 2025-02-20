<?php
// app/model/Offre.php

namespace App\Model;

class Offre extends BaseModel {
    public $id;
    public $titre;
    public $description;
    public $duree;
    public $remuneration;
    public $entreprise_id;
    public $date_debut;
    public $date_fin;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupère une offre par son ID.
     *
     * @param int $id
     * @return array|false
     */
    public static function findById($id) {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM offre WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde l'offre (insertion ou mise à jour).
     *
     * @return bool
     */
    public function save() {
        if (isset($this->id)) {
            // Mise à jour
            $stmt = $this->pdo->prepare("UPDATE offre SET titre = ?, description = ?, duree = DATEDIFF(?, ?), remuneration = ?, entreprise_id = ?, date_debut = ?, date_fin = ? WHERE id = ?");
            return $stmt->execute([
                $this->titre,
                $this->description,
                $this->date_fin,
                $this->date_debut,
                $this->remuneration,
                $this->entreprise_id,
                $this->date_debut,
                $this->date_fin,
                $this->id
            ]);
        } else {
            // Insertion
            $stmt = $this->pdo->prepare("INSERT INTO offre (titre, description, duree, remuneration, entreprise_id, date_debut, date_fin) VALUES (?, ?, DATEDIFF(?, ?), ?, ?, ?, ?)");
            $result = $stmt->execute([
                $this->titre,
                $this->description,
                $this->date_fin,
                $this->date_debut,
                $this->remuneration,
                $this->entreprise_id,
                $this->date_debut,
                $this->date_fin
            ]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $result;
        }
    }
}
