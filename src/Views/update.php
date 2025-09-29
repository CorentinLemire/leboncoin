<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php?url=login");
    exit;
}

include_once __DIR__ . "/templates/head.php";
include_once __DIR__ . "/templates/navbar.php";
?>

<div class="container mt-5">
    <h2 class="mb-4 text-primary">Modifier mon annonce</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text"
                class="form-control"
                id="title"
                name="title"
                value="<?= htmlspecialchars($annonce['a_title']) ?>"
                required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control"
                id="description"
                name="description"
                rows="4"
                required><?= htmlspecialchars($annonce['a_description']) ?></textarea>
        </div>

        <!-- Prix -->
        <div class="mb-3">
            <label for="price" class="form-label">Prix (€)</label>
            <input type="number"
                step="0.01"
                class="form-control"
                id="price"
                name="price"
                value="<?= htmlspecialchars($annonce['a_price']) ?>"
                required>
        </div>

        <!-- Photo actuelle -->
        <div class="mb-3">
            <label class="form-label">Photo actuelle</label><br>
            <img src="<?= $annonce['a_picture'] !== "nophoto.jpg"
                            ? '/uploads/' . htmlspecialchars($_SESSION['user']['username']) . '/' . htmlspecialchars($annonce['a_picture'])
                            : '/uploads/nophoto.jpg' ?>"
                alt="Photo actuelle"
                class="img-thumbnail"
                style="max-width: 200px;">
        </div>

        <!-- Nouvelle photo -->
        <div class="mb-3">
            <label for="picture" class="form-label">Changer la photo (optionnel)</label>
            <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
        </div>

        <!-- Boutons -->
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="index.php?url=profil" class="btn btn-secondary">Annuler</a>
    </form>
</div>
```