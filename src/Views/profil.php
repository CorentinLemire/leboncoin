<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php?url=login");
    exit;
}

$user = $_SESSION['user'];
?>

<?php
include_once __DIR__ . "/templates/head.php";
include_once __DIR__ . "/templates/navbar.php";
?>

<!-- Profil en haut -->
<div class="bg-primary text-white text-center py-4 mb-4 rounded-bottom shadow">
    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=ffffff&color=007bff&size=120"
        alt="Avatar" class="rounded-circle border border-3 border-white mb-2" style="width:90px;height:90px;object-fit:cover;">
    <h2 class="fw-bold"><?= htmlspecialchars($user['username']) ?></h2>
    <p><?= htmlspecialchars($user['email']) ?></p>

    <div class="mt-3">
        <a href="index.php?url=create" class="btn btn-light fw-bold me-2">Créer une annonce</a>
        <a href="index.php?url=logout" class="btn btn-danger fw-bold">Se déconnecter</a>
    </div>
</div>

<!-- Messages succès / erreur -->
<div class="container mb-3">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success">Annonce supprimée avec succès.</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'notallowed'): ?>
        <div class="alert alert-danger">Vous ne pouvez pas supprimer cette annonce.</div>
    <?php endif; ?>
</div>

<!-- Annonces de l’utilisateur -->
<div class="container">
    <h2 class="mb-4">Mes annonces</h2>

    <div class="row g-4">
        <?php if (!empty($annonces)): ?>
            <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Image responsive -->
                        <img src="<?= $annonce['a_picture'] !== "nophoto.jpg"
                                        ? '/uploads/' . htmlspecialchars($annonce['u_username']) . '/' . htmlspecialchars($annonce['a_picture'])
                                        : '/uploads/nophoto.jpg' ?>"
                            class="card-img-top img-fluid"
                            alt="<?= htmlspecialchars($annonce['a_title']) ?>">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary fw-bold"><?= htmlspecialchars($annonce['a_title']) ?></h5>
                            <p class="card-text mb-1">Date de création : <?= htmlspecialchars($annonce['a_publication']) ?></p>
                            <p class="card-text mb-3">Prix : <b><?= htmlspecialchars($annonce['a_price']) ?> €</b></p>

                            <div class="d-flex gap-2 mt-auto">
                                <a href="index.php?url=details/<?= $annonce['a_id'] ?>" class="btn btn-primary">
                                    Voir les détails
                                </a>
                                <a href="index.php?url=delete/<?= $annonce['a_id'] ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Es-tu sûr de vouloir supprimer cette annonce ?');">
                                    Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune annonce pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>

</html>