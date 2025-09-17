<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l’utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: index.php?url=login");
    exit;
}

$user = $_SESSION['user'];
?>

<style>
    body {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        min-height: 100vh;
    }

    .profile-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 70px);
        /* laisse la place pour la navbar */
    }

    .profile-card {
        max-width: 400px;
        width: 100%;
        padding: 20px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .profile-card h2 {
        margin-bottom: 15px;
    }

    .logo-header {
        max-height: 50px;
        margin-right: 10px;
    }
</style>

<?php
include_once __DIR__ . "/templates/head.php";
include_once __DIR__ . "/templates/navbar.php";
?>

<div class="profile-container">
    <div class="profile-card">
        <h2> Mon Profil</h2>
        <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>

        <a href="index.php?url=logout" class="btn btn-danger w-100 mt-3">Se déconnecter</a>
    </div>
</div>
</body>

</html>