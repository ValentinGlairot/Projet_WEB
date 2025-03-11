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

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Récupère une offre par son ID.
     *
     * @param int $id
     * @return array|false
     */
    public static function findById($id)
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT offre.*, entreprise.nom AS entreprise
            FROM offre
            JOIN entreprise ON offre.entreprise_id = entreprise.id
            WHERE offre.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    /**
     * Sauvegarde l'offre (insertion ou mise à jour).
     *
     * @return bool
     */
    public function save()
    {
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

    public function deleteById($id)
    {
        $pdo = \Database::getInstance();

        try {
            // Suppression des candidatures associées à l'offre
            $stmt = $pdo->prepare("DELETE FROM candidature WHERE offre_id = ?");
            $stmt->execute([$id]);

            // Suppression de l'offre après avoir supprimé les candidatures associées
            $stmt = $pdo->prepare("DELETE FROM offre WHERE id = ?");
            $stmt->execute([$id]);

            return true;
        } catch (\PDOException $e) {
            die("Erreur lors de la suppression de l'offre : " . $e->getMessage());
        }
    }


}
?>
