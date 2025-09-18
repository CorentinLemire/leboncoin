<?php

namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    public function create()
    {


        // $errors = [];
        // $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? 0;
            $u_id = $_SESSION['user']['id'] ?? 0;
            $picture = null;
            // Validation
            if (empty($title)) $errors['title'] = "Titre obligatoire";
            if (empty($description)) $errors['description'] = "Description obligatoire";
            if (!is_numeric($price) || $price <= 0) $errors['price'] = "Prix invalide";

            // Upload image
            $pictureName = '';
            if (!empty($_FILES['picture']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $pictureName = time() . '_' . basename($_FILES['picture']['name']);
                move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir . $pictureName);
            }

            // if (empty($errors)) {
            //     $annonce = new Annonce;
            //     echo 'test 3';
            // }


            if (empty($errors)) {

                $annonce = new Annonce();

                $result = $annonce->createAnnonce($title, $description, (float)$price, $pictureName, $u_id);

                if ($result) {
                    $success = "Annonce créée avec succès ";
                } else {
                    $errors['bdd'] = "Erreur lors de l'enregistrement.";
                }
            }
        }

        require_once __DIR__ . '/../Views/create.php';
    }

    // public function list()
    // {
    //     $annonce = new Annonce();
    //     $annonces = $annonce->getAllAnnonces();

    //     require_once __DIR__ . '/../Views/annonces.php';
    // }
}
