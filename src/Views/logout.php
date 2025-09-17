<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="card shadow p-4 text-center" style="max-width: 400px;">
        <h2 class="mb-3">Déconnexion</h2>
        <p>Voulez-vous vraiment vous déconnecter ?</p>

        <div class="d-flex justify-content-around mt-4">
            <!-- Bouton qui appelle ta fonction logout -->
            <a href="index.php?url=logout" class="btn btn-danger">Se déconnecter</a>

            <!-- Bouton pour annuler et revenir à l’accueil -->
            <a href="index.php?url=home" class="btn btn-secondary">Annuler</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>