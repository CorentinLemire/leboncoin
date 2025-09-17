<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoisinMarket</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS perso -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.8rem;
        }

        .btn-custom {
            margin-left: 0.5rem;
        }

        .logo-header {
            max-height: 50px;
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <?php
    include_once __DIR__ . "/templates/navbar.php";
    ?>

    <!-- Contenu principal -->
    <div class="container text-center mt-5">
        <h1>Bienvenue sur VoisinMarket !</h1>
        <p class="lead">Achetez et vendez près de chez vous facilement.</p>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>