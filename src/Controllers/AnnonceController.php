<?php

namespace App\Controllers;

use App\Models\Annonce;
use PDO;
use PDOException;

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

            if (empty($title)) $errors['title'] = "Titre obligatoire";
            if (empty($description)) $errors['description'] = "Description obligatoire";
            if (!is_numeric($price) || $price <= 0) $errors['price'] = "Prix invalide";

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
                    $errors['picture'] = "Fichier trop lourd (max 8 Mo)";
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
        require_once __DIR__ . '/../Views/home.php';
    }

    public function show($id)
    {
        $objAnnonce = new Annonce();
        $annonce = $objAnnonce->findById((int)$id);

        require_once __DIR__ . '/../Views/details.php';
    }

    public function delete($id, $userId)
    {
        $objAnnonce = new Annonce();
        $annonceInfo = $objAnnonce->findById($id);

        if (!$annonceInfo) {
            header("Location: index.php?url=home");
            exit;
        }

        $pictureName = $annonceInfo['a_picture'];
        $deleted = $objAnnonce->deletebyId((int)$id, (int)$userId);

        if ($deleted) {
            if (!empty($pictureName) && $pictureName !== 'nophoto.jpg') {
                $filePath = "uploads/" . $_SESSION['user']['username'] . "/" . $pictureName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            header("Location: index.php?url=profil&success=deleted");
        } else {
            header("Location: index.php?url=profil&error=deletefailed");
        }
        exit;
    }

    public function update($id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?url=login");
            exit;
        }

        $userId = (int) $_SESSION['user']['id'];
        $id = (int) $id;

        $annonceModel = new Annonce();
        $annonce = $annonceModel->findById($id);

        if (!$annonce || (int)$annonce['u_id'] !== $userId) {
            header("Location: index.php?url=profil&error=notallowed");
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? '';
            $picture = $annonce['a_picture'];

            if ($title === '') $errors['title'] = "Titre obligatoire";
            if ($description === '') $errors['description'] = "Description obligatoire";
            if ($price === '' || !is_numeric($price) || (float)$price <= 0) $errors['price'] = "Prix invalide";

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
                    $errors['picture'] = "Fichier trop lourd (max 8 Mo)";
                } else {
                    $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                    $newName = uniqid('', true) . '.' . $extension;

                    if (move_uploaded_file($tmp_name, $user_dir . $newName)) {
                        if (!empty($annonce['a_picture']) && $annonce['a_picture'] !== 'nophoto.jpg') {
                            $oldPath = $user_dir . $annonce['a_picture'];
                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }
                        $picture = $newName;
                    } else {
                        $errors['picture'] = "Erreur lors de l'upload du fichier";
                    }
                }
            }

            if (empty($errors)) {
                $ok = $annonceModel->updateAnnonce($id, $title, $description, (float)$price, $picture, $userId);

                if ($ok) {
                    header("Location: index.php?url=profil");
                    exit;
                } else {
                    $errors['general'] = "Erreur lors de la mise à jour.";
                }
            }
        }

        require_once __DIR__ . '/../Views/update.php';
    }
}
