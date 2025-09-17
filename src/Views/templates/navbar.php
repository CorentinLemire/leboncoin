<!-- Header / Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php?url=home">
            <img src="assets/img/logo.png" alt="logo" class="logo-header">
            VoisinMarket
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['url'] ?? 'home') === 'home' ? 'active' : '' ?>" href="index.php?url=home">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['url'] ?? '') === 'annonces' ? 'active' : '' ?>" href="index.php?url=annonces">Annonces</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['url'] ?? '') === 'about' ? 'active' : '' ?>" href="index.php?url=about">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['url'] ?? '') === 'contact' ? 'active' : '' ?>" href="index.php?url=contact">Contact</a>
                </li>
            </ul>


            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Si connecté -->
                    <a href="index.php?url=profil" class="btn btn-outline-primary me-2">
                        <i class="bi bi-person-fill"></i> <?= htmlspecialchars($_SESSION['user']['username']) ?>
                    </a>
                    <a href="index.php?url=logout" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                <?php else: ?>
                    <!-- Si pas connecté -->
                    <a href="index.php?url=login" class="btn btn-outline-primary me-2">Se connecter</a>
                    <a href="index.php?url=register" class="btn btn-primary">S’inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>