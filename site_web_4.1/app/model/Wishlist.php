<?php
// app/model/Wishlist.php

namespace App\Model;

class Wishlist extends BaseModel
{
    public $id;
    public $user_id;
    public $offre_id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Récupère la wishlist d'un utilisateur.
     *
     * @param int $user_id
     * @return array
     */
    public static function findByUserId($user_id)
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde l'entrée de la wishlist (insertion ou mise à jour).
     *
     * @return bool
     */
    public function save()
    {
        if (isset($this->id)) {
            $stmt = $this->pdo->prepare("UPDATE wishlist SET user_id = ?, offre_id = ? WHERE id = ?");
            return $stmt->execute([$this->user_id, $this->offre_id, $this->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO wishlist (user_id, offre_id) VALUES (?, ?)");
            $result = $stmt->execute([$this->user_id, $this->offre_id]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $result;
        }
    }
}
?>