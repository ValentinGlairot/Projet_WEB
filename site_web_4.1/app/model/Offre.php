<?php
// app/model/Offre.php

namespace App\Model;

class Offre extends BaseModel
{
    public $id;
    public $titre;
    public $description;
    public $duree;
    public $remuneration;
    public $entreprise_id;
    public $date_debut;
    public $date_fin;
    public $competences;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Récupère une offre par son ID, avec le nom de l'entreprise
     */
    public static function findById($id)
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT o.*,
                   e.nom AS entreprise,
                   (SELECT COUNT(*) FROM candidature c WHERE c.offre_id = o.id) as nb_candidats
            FROM offre o
            JOIN entreprise e ON o.entreprise_id = e.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde (insertion ou mise à jour)
     */
    public function save()
    {
        if (isset($this->id)) {
            // Mise à jour
            $stmt = $this->pdo->prepare("
                UPDATE offre
                SET titre = ?, description = ?,
                    duree = DATEDIFF(date_fin, date_debut),
                    remuneration = ?, entreprise_id = ?,
                    date_debut = ?, date_fin = ?, competences = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $this->titre,
                $this->description,
                $this->remuneration,
                $this->entreprise_id,
                $this->date_debut,
                $this->date_fin,
                $this->competences,
                $this->id
            ]);
        } else {
            // Insertion
            $stmt = $this->pdo->prepare("
                INSERT INTO offre
                    (titre, description, duree, remuneration,
                     entreprise_id, date_debut, date_fin, competences)
                VALUES (?, ?, DATEDIFF(?, ?), ?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([
                $this->titre,
                $this->description,
                $this->date_fin,
                $this->date_debut,
                $this->remuneration,
                $this->entreprise_id,
                $this->date_debut,
                $this->date_fin,
                $this->competences
            ]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $result;
        }
    }

    /**
     * Supprimer
     */
    public function deleteById($id)
    {
        $pdo = \Database::getInstance();
        try {
            // Supprimer d'abord les candidatures associées
            $stmt = $pdo->prepare("DELETE FROM candidature WHERE offre_id = ?");
            $stmt->execute([$id]);

            // Puis supprimer l'offre
            $stmt = $pdo->prepare("DELETE FROM offre WHERE id = ?");
            $stmt->execute([$id]);

            return true;
        } catch (\PDOException $e) {
            die("Erreur lors de la suppression de l'offre : " . $e->getMessage());
        }
    }
}
?>