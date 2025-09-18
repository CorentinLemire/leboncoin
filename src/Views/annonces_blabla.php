<h2>Liste des annonces</h2>

<?php if (!empty($annonces)): ?>
    <?php foreach ($annonces as $annonce): ?>
        <div class="annonce-card">
            <h3><?= htmlspecialchars($annonce['a_title']) ?></h3>
            <p>Prix : <?= htmlspecialchars($annonce['a_price']) ?> €</p>
            <p><?= htmlspecialchars($annonce['a_description']) ?></p>
            <p>Publié le : <?= htmlspecialchars($annonce['a_publication']) ?></p>
            <?php if (!empty($annonce['a_picture'])): ?>
                <img src="uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" width="200">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucune annonce trouvée.</p>
<?php endif; ?>