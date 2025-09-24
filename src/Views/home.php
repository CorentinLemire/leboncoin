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

        .annonces-container {
            margin-top: 40px;
        }

        .annonce-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            text-align: left;
        }

        .annonce-card h3 {
            margin-bottom: 10px;
            color: #007bff;
        }

        .annonce-card img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <?php
    include_once __DIR__ . "/templates/navbar.php";

    use App\Models\Annonce;

    // Récupère les annonces depuis ton modèle
    $annonceModel = new Annonce();
    $annonces = $annonceModel->findAll();
    ?>

    <!-- Contenu principal -->
    <div class="container text-center mt-5">
        <h1>Bienvenue sur VoisinMarket !</h1>
        <p class="lead">Achetez et vendez près de chez vous facilement.</p>
    </div>

    <!-- Liste des annonces -->
    <div class="container annonces-container">
        <h2 class="mb-4"> Dernières annonces</h2>

        <?php if (!empty($annonces)): ?>
            <div class="row">
                <?php foreach ($annonces as $annonce): ?>
                    <div class="col-md-4">
                        <div class="annonce-card">
                            <h3><?= htmlspecialchars($annonce['a_title']) ?></h3>
                            <p><strong>Prix :</strong> <?= htmlspecialchars($annonce['a_price']) ?> €</p>
                            <p><?= htmlspecialchars($annonce['a_description']) ?></p>
                            <p><small>Publié le <?= htmlspecialchars($annonce['a_publication'] ?? '') ?></small></p>

                            <!-- Bouton détail -->
                            <a href="index.php?url=details/<?= $annonce['a_id'] ?>" class="btn btn-primary mt-2">
                                Voir détail
                            </a>

                            <!-- Image -->
                            <div class="w-100 h-50 d-flex align-items-center justify-content-center">
                                <img
                                    src="<?= $annonce['a_picture'] !== "nophoto.jpg"
                                                ? '/uploads/' . $annonce['u_username'] . '/' . htmlspecialchars($annonce['a_picture'])
                                                : '/uploads/nophoto.jpg' ?>"
                                    class="img-fluid w-100 h-100 object-fit-contain"
                                    alt="<?= htmlspecialchars($annonce['a_title']) ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune annonce pour le moment.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>