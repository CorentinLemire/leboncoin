<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{

    public function register()
    {
        $errors = [];
        $username = $email = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            $regexNom   = '/^[\p{L}][\p{L} \'\-]{1,99}$/u'; // nom complet
            $regexEmail = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';

            // Vérifications
            if (!preg_match($regexNom, $username)) {
                $errors[] = "Le nom n'est pas valide (2-100 caractères, lettres et tirets seulement).";
            }
            if (!preg_match($regexEmail, $email)) {
                $errors[] = "L'email n'est pas valide.";
            }
            if ($password !== $confirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if (User::checkMail($email) === true) {
                $errors['email'] = "L'adresse mail est déjà utilisée, veuillez réessayer.";
            }
            if (User::checkUsername($username) === true) {
                $errors['username'] = "Le nom d'utilisateur est déjà utilisé, veuillez réessayer.";
            }

            // ✅ Si pas d'erreurs : on enregistre et on redirige vers home
            if (empty($errors)) {
                $usermodel = new User();
                $usermodel->createUser($email, $password, $username);

                // Démarre la session si pas déjà active
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Stocke l’utilisateur dans la session
                $_SESSION['user'] = [
                    'username' => $username,
                    'email' => $email
                ];

                header("Location: index.php?=home");
                exit;
            }

            // Affichage des erreurs
            foreach ($errors as $err) {
                echo "<p style='color:red'>" . htmlspecialchars($err) . "</p>";
            }
        }

        require_once __DIR__ . "/../Views/register.php";
    }
    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // je créé un tableau d'erreurs vide car pas d'erreur
            $errors = [];

            if (isset($_POST["email"])) {
                // on va vérifier si c'est vide
                if (empty($_POST["email"])) {
                    // si c'est vide, je créé une erreur dans mon tableau
                    $errors['email'] = 'Mail obligatoire';
                }
            }

            if (isset($_POST["password"])) {
                // on va vérifier si c'est vide
                if (empty($_POST["password"])) {
                    // si c'est vide, je créé une erreur dans mon tableau
                    $errors['password'] = 'Mot de passe obligatoire';
                }
            }

            // nous vérifions s'il n'y a pas d'erreur = on regarde si le tableau est vide.
            if (empty($errors)) {

                if (User::checkMail($_POST["email"])) {

                    $userInfos = new User();
                    $userInfos->getUserInfosByEmail($_POST["email"]);

                    if (password_verify($_POST["password"], $userInfos->password)) {

                        // Nous allons créer une variable de session "user" avec les infos du User
                        $_SESSION["user"]["id"] = $userInfos->id;
                        $_SESSION["user"]["email"] = $userInfos->email;
                        $_SESSION["user"]["username"] = $userInfos->username;
                        $_SESSION["user"]["inscription"] = $userInfos->inscription;

                        // Nous allons ensuite faire une redirection sur une page choisie
                        header("Location: index.php?url=profil");
                    } else {
                        $errors['connexion'] = 'Mail ou Mot de passe incorrect';
                    }
                } else {
                    $errors['connexion'] = 'Mail ou Mot de passe incorrect';
                }
            }
        }

        require_once __DIR__ . "/../Views/login.php";
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: index.php?url=home');
    }
    public function profil()
    {
        require_once __DIR__ . "/../Views/profil.php";
    }
}
