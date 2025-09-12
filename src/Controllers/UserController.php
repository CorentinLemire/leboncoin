<?php

namespace App\Controllers;

class UserController
{
    public function index()
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
            $regexPwd   = '/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).*$/';

            // Vérifications
            if (!preg_match($regexNom, $username)) {
                $errors[] = "Le nom n'est pas valide (2-100 caractères, lettres et tirets seulement).";
            }
            if (!preg_match($regexEmail, $email)) {
                $errors[] = "L'email n'est pas valide.";
            }
            if (!preg_match($regexPwd, $password)) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 symbole.";
            }
            if ($password !== $confirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
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

        require_once __DIR__ . "/../Views/login.php";
    }
}
