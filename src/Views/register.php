<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription sécurisée</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        form {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background: #FF6B00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #e65f00;
        }
    </style>
</head>

<body>
    <form method="post">
        <h2>Créer un compte</h2>
        <input type="text" name="username" placeholder="username" value="<?= htmlspecialchars($username) ?>">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
        <input type="password" name="password" placeholder="Mot de passe">
        <input type="password" name="confirm" placeholder="Confirmer le mot de passe">
        <button type="submit">S'inscrire</button>
    </form>
</body>

</html>