<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails de l’annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .annonce-detail {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        .annonce-detail img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="annonce-detail">

            <?php if (empty($annonce)): ?>
                <div class="alert alert-danger">Annonce introuvable.</div>
            <?php else: ?>
                <h1><?= htmlspecialchars($annonce['a_title']) ?></h1>
                <p><strong>Prix :</strong> <?= htmlspecialchars($annonce['a_price']) ?> €</p>
                <p><?= nl2br(htmlspecialchars($annonce['a_description'])) ?></p>

                <?php if (!empty($annonce['a_picture']) && $annonce['a_picture'] !== "uploads/nophoto.jpg"): ?>
                    <img
                        src="<?= $annonce['a_picture'] !== "nophoto.jpg"
                                    ? '/uploads/' . $annonce['u_username'] . '/' . htmlspecialchars($annonce['a_picture'])
                                    : '/uploads/nophoto.jpg' ?>"
                        class="img-fluid"
                        style="max-width: 300px; max-height: 200px; object-fit: contain;"
                        alt="<?= htmlspecialchars($annonce['a_title']) ?>">
                <?php else: ?>
                    <img
                        src="/uploads/nophoto.jpg"
                        class="img-fluid"
                        style="max-width: 300px; max-height: 200px; object-fit: contain;"
                        alt="Pas de photo" />
                <?php endif; ?>

            <?php endif; ?>


            <a href="index.php?url=home" class="btn btn-secondary mt-3">Retour</a>
        </div>
    </div>
</body>

</html>