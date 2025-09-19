<?php

namespace App\Controllers;

use App\Models\Annonce;

class AnnonceController
{
    public function create()
    {

        $errors = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? 0;
            $u_id = $_SESSION['user']['id'] ?? 0;

            // Validation
            if (empty($title)) $errors['title'] = "Titre obligatoire";
            if (empty($description)) $errors['description'] = "Description obligatoire";
            if (!is_numeric($price) || $price <= 0) $errors['price'] = "Prix invalide";

            // Upload image
            $pictureName = '';
            if (!empty($_FILES['picture']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Crée le dossier si inexistant
                }
                $pictureName = time() . '_' . basename($_FILES['picture']['name']);
                move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir . $pictureName);
            }

            // Si aucune erreur → insertion BDD
            if (empty($errors)) {
                $annonce = new Annonce();
                $result = $annonce->createAnnonce($title, $description, (float)$price, $pictureName, $u_id);

                if ($result) {
                    // ✅ Redirection vers la page profil après succès
                    header("Location: index.php?url=annonces");
                    exit;
                } else {
                    $errors['bdd'] = "Erreur lors de l'enregistrement.";
                }
            }
        }

        require_once __DIR__ . '/../Views/create.php';
    }
}
