<?php

namespace App\Models;

// on utilise `use` pour indiquer qu'il s'agit de globales PHP et non de classe
use PDO;
use PDOException;


class Database
{
    /**
     * Permet de créer une instance de PDO
     * @return object Instance PDO ou Null
     */
    public static function createInstancePDO(): ?PDO
    {
        // on déclare des variables : il s'agit des paramètres de notre container docker
        $db_host = 'db';
        $db_name = 'leboncoin';
        $db_user = 'root';
        $db_password = 'root';

        try {
            // j'utilise les variables plus haut
            $pdo = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8', $db_user, $db_password);
            // A Activer seulement en developpement pour gagner en visibilité sur les erreurs SQL
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // On retourne l'instance de PDO qui sera un objet
            return $pdo;
        } catch (PDOException $e) {
            // test unitaire pour vérifier que la connexion à la base de données fonctionne
            // echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }
}
echo "je n'apparait pas";
