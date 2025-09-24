<?php

namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    public function create()
    {
        $errors = [];
        $picture = "nophoto.jpg";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? 0;
            $u_id = $_SESSION['user']['id'] ?? 0;

            // Validation basique
            if (empty($title)) $errors['title'] = "Titre obligatoire";
            if (empty($description)) $errors['description'] = "Description obligatoire";
            if (!is_numeric($price) || $price <= 0) $errors['price'] = "Prix invalide";

            // Vérification upload image
            if (!empty($_FILES['picture']['tmp_name'])) {
                $username = $_SESSION['user']['username'];
                $uploads_dir = __DIR__ . '/../../public/uploads/';
                $user_dir = $uploads_dir . $username . '/';

                if (!is_dir($user_dir)) {
                    mkdir($user_dir, 0755, true);
                }

                $tmp_name = $_FILES['picture']['tmp_name'];
                $mime = mime_content_type($tmp_name);
                $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $maxSize = 8 * 1024 * 1024;
                $size = $_FILES['picture']['size'];

                if (!in_array($mime, $allowed)) {
                    $errors['picture'] = "Type de fichier non autorisé";
                } elseif ($size > $maxSize) {
                    $errors['picture'] = "Fichier trop lourd (max 100 Mo)";
                } else {
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                    $newName = uniqid('', true) . '.' . $extension;

                    if (move_uploaded_file($tmp_name, $user_dir . $newName)) {

                        $picture = $newName;
                    } else {
                        $errors['picture'] = "Erreur lors de l'upload du fichier";
                    }
                }
            }

            // Insertion BDD
            if (empty($errors)) {
                $objAnnonce = new Annonce();
                $objAnnonce->createAnnonce($title, $description, (float)$price, $picture, $u_id);

                header("Location: index.php?url=profil");
                exit;
            }
        }

        require_once __DIR__ . '/../Views/create.php';
    }

    public function index()
    {
        require_once __DIR__ . "/../Views/home.php";
    }

    public function show($id)
    {

        $objAnnonce = new Annonce();
        $annonce = $objAnnonce->findById((int)$id);
        // if (!$annonce) {
        //     require_once __DIR__ . '/../Views/page404.php';
        //     return;
        // }

        require_once __DIR__ . '/../Views/details.php';
    }
    public function delete($id, $userId)
    {
        // On récupère l'annonce avec l'id via la méthode findById
        $objdeleteAnnonce = new Annonce();
        $annonceInfo = $objdeleteAnnonce->findById($id);

        if ($annonceInfo == false) {
            header("Location: index.php?url=home");
        } else {

            // On récupère le nom de la photo pour la supprimer ensuite localement
            $pictureName = $annonceInfo['a_picture'];

            // On supprime l'annonce via la methode deletebyId
            $deleteAnnonce = $objdeleteAnnonce->deletebyId((int) $id, (int) $userId);

            if ($deleteAnnonce === true) {
                unlink("uploads/" . $_SESSION['user']['username'] . "/" . $pictureName);
                header("Location: index.php?url=profil");
            } else {
                header("Location: index.php?url=profil");
            }
        }
    }
}
