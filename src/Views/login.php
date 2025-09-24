<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - VoisinMarket</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            margin-bottom: 25px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Se connecter</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="email" class="form-label"> email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Votre nom d'utilisateur" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <span class="text-danger"><?= $errors['connexion'] ?? '' ?></span>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <div class="text-center mt-3">
            <p>Pas encore de compte ? <a href="index.php?url=register">S'inscrire</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>