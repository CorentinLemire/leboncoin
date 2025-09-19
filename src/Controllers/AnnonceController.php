<?php

namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    /**
     * Méthode affichant la liste des annonces
     *
     * @return void
     */
    public function index(): void
    {
        $objAnnonce = new Annonce();
        $annonces = $objAnnonce->findAll();
        require_once __DIR__ . "/../Views/annonces.php";
    }

    public function create(): void
    {

        // on contrôle si une variable de session User est présente
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?url=login");
            exit;
        }

        // traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // On crée un tableau d'erreurs vide
            $errors = [];

            // on vérifie que le titre n'est pas vide
            if (empty($_POST['title'])) {
                $errors['title'] = 'Le titre est obligatoire';
            }

            // on vérifie que la description n'est pas vide
            if (empty($_POST['description'])) {
                $errors['description'] = 'La description est obligatoire';
            }

            // on vérifie que le prix n'est pas vide
            if (empty($_POST['price'])) {
                $errors['price'] = 'Un prix est obligatoire';
                // on vérifie que le prix est un nombre positif : attention de bien convertir les virgules en point car is_numeric ne prends pas en compte les virgules
                // nous utilisons donc str_replace()
            } elseif (!is_numeric(str_replace(",", ".", $_POST['price']))) {
                $errors['price'] = 'Le prix doit être un nombre';
            } elseif ($_POST['price'] < 0) {
                $errors['price'] = 'Le prix ne peut pas être inférieur à 0';
            }



            // on controle que la taille du fichier n'a pas fait buggé notre upload à l'aide d'un isset()
            if (!isset($_FILES['picture'])) {
                $errors['picture'] = 'Le fichier est beaucoup trop volumineux';

                // on lance les vérifications uniquement si l'utilisateur à cliquer sur le bouton upload et que le fichier est bien stocké dans un fichier temporaire donc pas error = 0
            } else if ($_FILES['picture']['error'] === 0) {

                // ------------------------------------------------------------------------------------------------
                // nous allons faire un traitement plus long pour la photo car pas mal de paramètres sont à vérifier
                // 1 : la photo est bien une photo et qu'elle est au format autorisé
                // 2 : la photo ne dépasse pas une c
                // ------------------------------------------------------------------------------------------------

                // on créé une variable pour faciliter la manipulation du fichier uploadé via un formulaire qui est stocké dans un ficher temporaire
                $file = $_FILES['picture']['tmp_name'];
                // on stock également son extension dans une variable qui nous servira plus tard
                $fileExtension = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
                // on va créer un tableau des types MIME authorisés
                $mimeOk = ['image/jpeg', 'image/webp', 'image/png'];
                // nous allons définir l'emplacement ou nous allons stocker les images : toutes les images seront dans le répertoire 'uploads'
                $uploadsDir = __DIR__ . "/../../public/uploads/";
                // Nous allons Vérifier si le dossier existe, car si il est vide, il ne sera pas présent dans le repo
                if (!is_dir($uploadsDir)) {
                    // Si non présent, nous allons le créer avec les bons droits (ici 0755, parfait pour du upload de fichier)
                    mkdir($uploadsDir, 0755, true);
                }

                // ETAPE 1 - Nous allons regarder le MIME du fichier pour nous assurer qu'il s'agit bien d'une image
                // Nous allons également regarder si le format est autorisé

                // création d'une ressource Fileinfo pour obtenir le MIME type
                $fileInfos = finfo_open(FILEINFO_MIME_TYPE);

                // on récupère le type MIME qui sera du type 'image/jpeg' ou 'image/png'
                $mime = finfo_file($fileInfos, $file);

                // on regarde dans notre tableau, si le format est autorisé
                if (!in_array($mime, $mimeOk, 1)) {
                    $errors['picture'] = 'Attention, votre image doit être au format : jpeg, png ou webp';

                    // ETAPE 2 - Nous allons controller la taille de l'image
                    // -> 8 Mo max
                } else if ($_FILES["picture"]["size"] > (8 * 1024 * 1024)) {
                    $errors['picture'] = 'La photo est trop grande 8 Mo max';
                }

                if (empty($errors)) {

                    move_uploaded_file($file, $uploadsDir . 'titi.jpg');
                }
            }
        }
        require_once __DIR__ . "/../Views/create.php";
    }
}
