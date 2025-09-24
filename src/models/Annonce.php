<?php

// Nom du dossier virtuel "namespace" pour les Models
namespace App\Models;

use App\Models\Database;

// On va utiliser PDO et PDOException
use PDO;
use PDOException;

class Annonce
{

    public int $id;
    public string $title;
    public string $description;
    public string $price;
    public string $picture;
    public string $userId;
    /**
     * Permet de créer un utilisateur dans la table users
     * @param string $title
     * @param string $description
     * @param string $price
     * @param string $picture
     * @param string $userId
     * @return bool true si l'insertion a réussi, false en cas d'erreur
     */
    public function createAnnonce(string $title, string $description, float $price, ?string $picture, int $u_id): bool
    {

        try {
            // Creation d'une instance de connexion à la base de données
            $pdo = Database::createInstancePDO();

            // test si la connexion est ok
            if (!$pdo) {
                // pas de connexion, on return false
                return false;
            }

            // requête SQL pour insérer un utilisateur dans la table users
            $sql = 'INSERT INTO `annonces` (`a_title`, `a_description`, `a_price`, `a_picture`, `u_id`) VALUES (:a_title, :a_description , :a_price, :a_picture, :u_id)';

            $stmt = $pdo->prepare($sql);


            $stmt->bindValue(':a_title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':a_description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':a_price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':a_picture', $picture, PDO::PARAM_STR);
            $stmt->bindValue(':u_id', $u_id, PDO::PARAM_STR);


            return $stmt->execute();
        } catch (PDOException $e) {
            // En cas d'erreur, on peut la logger ou l'afficher
            echo "Erreur lors de la création de l'annonce : " . $e->getMessage();
            return false;
        }
    }

    public function findAll(): array
    {

        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) {
                // return false;
                return [];
            }

            // requête SQL pour récupérer toute la table annonces
            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username` FROM `annonces` NATURAL JOIN `users`';


            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // test unitaire pour connaitre la raison de l'echec
            // echo 'Erreur : ' . $e->getMessage();
            // return false;
            return [];
        }
    }
    public function findById(int $id): ?array
    {
        try {
            $pdo = Database::createInstancePDO();
            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username` FROM `annonces` NATURAL JOIN `users` WHERE a_id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $annonce = $stmt->fetch(PDO::FETCH_ASSOC);

            return $annonce ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
    public function findByUserId(int $userId): array
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) {
                return [];
            }

            $sql = "SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, 
                       `a_publication`, `u_id`, `u_username` 
                FROM `annonces` 
                NATURAL JOIN `users` 
                WHERE u_id = :u_id 
                ORDER BY a_publication DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':u_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function findByUser(int $userId): ?array
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) {
                return null;
            }
            $sql = 'SELECT `a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `u_username` FROM `annonces` NATURAL JOIN `users` WHERE u_id = :userId';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $annonceUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $annonceUser;
        } catch (PDOException $e) {
            return null;
        }
    }
    public function deletebyId(int $id, int $userId)
    {
        try {
            $pdo = Database::createInstancePDO();
            if (!$pdo) {
                return null;
            }
            // Je supprime toutes les colonnes de ma table annonces, pour le cas ou on pointe sur le annonce id et sur l'user id
            $sql = 'DELETE FROM `annonces` WHERE a_id = :id AND u_id = :userId';

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            // on retourne true si l'execute a bien fonctionné
            return true;
        } catch (PDOException $e) {
            return null;
        }
    }
}
