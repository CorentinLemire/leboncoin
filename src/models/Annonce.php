<?php

namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class Annonce
{
    // Ici on définit les infos principales d'une annonce
    public int $id;             // L'identifiant unique de l'annonce (généré par la base de données)
    public string $title;       // Le titre de l'annonce
    public string $description; // Le texte de description de l'annonce
    public string $price;       // Le prix (stocké comme texte ici, mais souvent c'est un nombre)
    public string $picture;     // L'image de l'annonce (chemin vers une image)
    public string $userId;      // L'identifiant de l'utilisateur qui a créé l'annonce

    /**
     * Fonction qui ajoute une nouvelle annonce dans la base de données
     */
    public function createAnnonce(string $title, string $description, float $price, ?string $picture, int $u_id): bool
    {
        try {
            // On récupère la connexion à la base
            $pdo = Database::createInstancePDO();
            if (!$pdo) return false; // Si la connexion échoue, on arrête

            // On écrit la requête SQL pour insérer une annonce
            $sql = 'INSERT INTO `annonces` (`a_title`, `a_description`, `a_price`, `a_picture`, `u_id`)
                    VALUES (:a_title, :a_description, :a_price, :a_picture, :u_id)';

            // On prépare la requête (plus sécurisé que concaténer les valeurs)
            $stmt = $pdo->prepare($sql);

            // On associe chaque valeur à un paramètre SQL
            $stmt->bindValue(':a_title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':a_description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':a_price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':a_picture', $picture, PDO::PARAM_STR);
            $stmt->bindValue(':u_id', $u_id, PDO::PARAM_INT);

            // On exécute la requête et on retourne vrai ou faux
            return $stmt->execute();
        } catch (PDOException $e) {
            // Si erreur, on l'affiche
            echo "Erreur lors de la création de l'annonce : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Récupère toutes les annonces avec les infos de l'utilisateur
     */
    public function findAll(): array
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) return []; // Si pas de connexion, retourne tableau vide

            // On sélectionne toutes les annonces avec les infos de l'utilisateur (grâce au NATURAL JOIN)
            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username`
                    FROM `annonces` NATURAL JOIN `users`';

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // On récupère toutes les lignes dans un tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupère une annonce précise grâce à son identifiant
     */
    public function findById(int $id): ?array
    {
        try {
            $pdo = Database::createInstancePDO();

            // On sélectionne une seule annonce avec son ID
            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username`
                    FROM `annonces` NATURAL JOIN `users` WHERE a_id = :id';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $annonce = $stmt->fetch(PDO::FETCH_ASSOC); // On récupère une seule ligne
            return $annonce ?: null; // Retourne null si rien trouvé
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Récupère toutes les annonces publiées par un utilisateur
     */
    public function findByUserId(int $userId): array
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) return [];

            // On récupère toutes les annonces de l'utilisateur donné
            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username`
                    FROM `annonces` NATURAL JOIN `users`
                    WHERE u_id = :u_id
                    ORDER BY a_publication DESC';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':u_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Autre version pour récupérer les annonces d'un utilisateur
     */
    public function findByUser(int $userId): ?array
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) return null;

            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username`
                    FROM `annonces` NATURAL JOIN `users`
                    WHERE u_id = :userId';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Supprime une annonce si l'ID correspond et que c'est bien le bon utilisateur
     */
    public function deletebyId(int $id, int $userId): bool
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) return false;

            $sql = 'DELETE FROM `annonces` WHERE a_id = :id AND u_id = :userId';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Met à jour une annonce existante (seul le propriétaire peut la modifier)
     */
    public function updateAnnonce(int $id, string $title, string $description, float $price, string $picture, int $userId): bool
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) return false;

            $sql = "UPDATE annonces
                    SET a_title = :title,
                        a_description = :description,
                        a_price = :price,
                        a_picture = :picture
                    WHERE a_id = :id AND u_id = :u_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':picture', $picture, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':u_id', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
