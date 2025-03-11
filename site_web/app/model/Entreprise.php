<?php
// app/model/Entreprise.php

namespace App\Model;

class Entreprise extends BaseModel
{
    public $id;
    public $nom;
    public $secteur;
    public $ville;
    public $taille;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Récupère les informations d'une entreprise par son ID.
     *
     * @param int $id
     * @return array|false
     */
    public static function findById($id)
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde l'entreprise (insertion ou mise à jour).
     *
     * @return bool
     */
    public function save()
    {
        if (isset($this->id)) {
            $stmt = $this->pdo->prepare("UPDATE entreprise SET nom = ?, secteur = ?, ville = ?, taille = ? WHERE id = ?");
            return $stmt->execute([$this->nom, $this->secteur, $this->ville, $this->taille, $this->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO entreprise (nom, secteur, ville, taille) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$this->nom, $this->secteur, $this->ville, $this->taille]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $result;
        }
    }
}
