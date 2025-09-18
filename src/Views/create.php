<?php

?>

<!DOCTYPE html>
<html lang="fr">

<?php include_once __DIR__ . "/templates/head.php" ?>


<body>


    <?php include_once __DIR__ . "/templates/navbar.php" ?>



    <div class="">

        <h2 class="text-center">Créer une annonce</h2>
        <form method="post" class="form-container mx-auto ">

            <!-- Titre -->
            <div class="">
                <label for="title" class="form-label">
                    <b>Titre de l'annonce : </b><span class="text-danger">* <i><?= isset($errors['title']) ? htmlspecialchars($errors['title']) : '' ?></i></span>
                </label>
                <input type="text" name="title" class="form-control" id="title" placeholder="Entrez le titre..." value="<?= $_POST['title'] ?? "" ?>">
            </div>





            <!-- Photo -->
            <div class="">
                <label for="photo" class="form-label">
                    <b>Photo : </b>
                </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile">
                </div>
            </div>
            <!-- Description -->
            <div class="">
                <label for="description" class="form-label">
                    <b>Description : </b><span class="text-danger">* <i><?= isset($errors['description']) ? htmlspecialchars($errors['description']) : '' ?></i></span>
                </label>
                <textarea class="form-control" name="description" id="description" placeholder="Entrez votre description..." rows="5"><?= $_POST['description'] ?? "" ?></textarea>


            </div>
            <!-- Prix -->
            <div class="">
                <label for="price" class="form-label">
                    <b>Prix : </b><span class="text-danger">* <i><?= isset($errors['price']) ? htmlspecialchars($errors['price']) : '' ?></i></span>
                </label>
                <input type="number" name="price" class="form-control" id="price" placeholder="Entrez le prix..." value="<?= $_POST['price'] ?? "" ?>">

            </div>
            <div class="text-danger">* Champs obligatoires</div>
            <div class="text-center mt-5">
                <button type="submit" class="btn">Créer l'annonce</button>
            </div>

        </form>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>