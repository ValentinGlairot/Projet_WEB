<?php
// app/model/Utilisateur.php

namespace App\Model;

class Utilisateur extends BaseModel
{
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $role;
    public $password_hash;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Récupère un utilisateur par son email.
     *
     * @param string $email
     * @return array|false
     */
    public static function findByEmail($email)
    {
        $pdo = \Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Sauvegarde l'utilisateur (insertion ou mise à jour).
     *
     * @return bool
     */
    public function save()
    {
        if (isset($this->id)) {
            $stmt = $this->pdo->prepare("UPDATE user SET nom = ?, prenom = ?, email = ?, role = ?, password_hash = ? WHERE id = ?");
            return $stmt->execute([$this->nom, $this->prenom, $this->email, $this->role, $this->password_hash, $this->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO user (nom, prenom, email, role, password_hash) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$this->nom, $this->prenom, $this->email, $this->role, $this->password_hash]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $result;
        }
    }
}
?>