<?php

namespace App\Models;

use App\Models\Database;

use PDO;
use PDOException;

class User
{

    public int $id;
    public string $email;
    public string $password;
    public string $username;
    public string $inscription;

    /**
     * Permet de créer un utilisateur dans la table users
     * @param string $email
     * @param string $password
     * @param string $username
     * @return bool true si l'insertion a réussi, false en cas d'erreur
     */
    public function createUser(string $email, string $password, string $username): bool
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
            $sql = 'INSERT INTO `users` (`u_email`, `u_password`, `u_username`) VALUES (:email, :password , :username)';

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On associe chaque paramètre nommé de la requête (:email, :password, :username)
            // avec la valeur correspondante en PHP, en précisant leur type (ici string).
            // Grâce aux requêtes préparées, cela empêche toute injection SQL.
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);

            // On exécute la requête préparée. La méthode renvoie true si tout s’est bien passé,
            // false sinon. 
            // NB : Avec PDO configuré en mode ERRMODE_EXCEPTION, une erreur déclenchera une exception.
            return $stmt->execute();
        } catch (PDOException $e) {
            // test unitaire pour connaitre la raison de l'echec
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de vérifier si un mail existe déjà dans la table users
     * @param string $email
     * @return bool true si le mail existe, false s'il n'existe pas
     */
    public static function checkMail(string $email): bool

    {

        try {
            // Creation d'une instance de connexion à la base de données
            $pdo = Database::createInstancePDO();

            // test si la connexion est ok
            if (!$pdo) {
                // pas de connexion, on return false
                return false;
            }

            // Requête qui teste si un email existe déjà.
            // SELECT 1 → on ne demande qu’un "1" (pas besoin des infos de l’utilisateur).
            // LIMIT 1 → on arrête la recherche dès le premier résultat trouvé.
            $sql = 'SELECT 1 FROM `users` WHERE `u_email` = :email LIMIT 1';

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On associe le paramètre nommé :email avec la valeur contenue dans $email,
            // en précisant qu’il s’agit d’une chaîne (PDO::PARAM_STR).
            // Cela permet à PDO de traiter correctement la valeur et d’éviter toute injection SQL.
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);

            // On exécute la requête
            $stmt->execute();

            // Récupère la première colonne du premier résultat de la requête.
            // Ici, comme la requête fait "SELECT 1", on obtiendra soit 1 si l’email existe,
            // soit false si aucun résultat n’est trouvé.
            $result = $stmt->fetchColumn();

            if ($result !== false) {
                // une ligne a été trouvée -> l'email existe déjà
                return true;
            } else {
                // aucune ligne trouvée -> l'email n'existe pas
                return false;
            }
        } catch (PDOException $e) {
            // test unitaire pour connaitre la raison de l'echec
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de vérifier si un username existe déjà dans la table users
     * @param string $username
     * @return bool true si le username existe, false s'il n'existe pas
     */
    public static function checkUsername(string $username): bool
    {
        try {
            // Création d'une instance de connexion à la base de données
            $pdo = Database::createInstancePDO();

            // Vérifie si la connexion est ok
            if (!$pdo) {
                // pas de connexion, on retourne false
                return false;
            }

            // Requête qui teste si un username existe déjà.
            // SELECT 1 → on ne demande qu’un "1" (pas besoin des infos de l’utilisateur).
            // LIMIT 1 → on arrête la recherche dès le premier résultat trouvé.
            $sql = 'SELECT 1 FROM `users` WHERE `u_username` = :username LIMIT 1';

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On associe le paramètre nommé :username avec la valeur contenue dans $username,
            // en précisant qu’il s’agit d’une chaîne (PDO::PARAM_STR).
            // Cela permet à PDO de traiter correctement la valeur et d’éviter toute injection SQL.
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);

            // On exécute la requête
            $stmt->execute();

            // Récupère la première colonne du premier résultat de la requête.
            // Ici, comme la requête fait "SELECT 1", on obtiendra soit 1 si le username existe,
            // soit false si aucun résultat n’est trouvé.
            $result = $stmt->fetchColumn();

            if ($result !== false) {
                // une ligne a été trouvée -> le username existe déjà
                return true;
            } else {
                // aucune ligne trouvée -> le username n'existe pas
                return false;
            }
        } catch (PDOException $e) {
            // Test unitaire pour connaître la raison de l’échec
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }


    /**
     * Permet de récupérer les infos d'un utilisateur via son email
     * et d'hydrater l'objet User avec les données récupérées
     * @param string $email
     * @return bool true si l'utilisateur a été trouvé et l'objet hydraté, false en cas d'erreur ou si l'utilisateur n'existe pas
     */
    public function getUserInfosByEmail(string $email): bool
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
            $sql = "SELECT * FROM `users` WHERE `u_email` = :email";

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On associe chaque paramètre nommé de la requête (:email, :password, :username)
            // avec la valeur correspondante en PHP, en précisant leur type (ici string).
            // Grâce aux requêtes préparées, cela empêche toute injection SQL.
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);

            // On execute la réquête
            $stmt->execute();

            // On recupère les données via un fetch() : se sera un objet
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            // On hydrate notre objet avec les valeurs du User
            $this->id = $user->u_id;
            $this->email = $user->u_email;
            $this->password = $user->u_password;
            $this->username = $user->u_username;
            $this->inscription = $user->u_inscription;

            return true;
        } catch (PDOException $e) {
            // test unitaire pour connaitre la raison de l'echec
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}
