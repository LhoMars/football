<nav class="navbar fixed-top navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid" style="margin-left: 5%; margin-right: 5%">
        <a href="<?= INCLUDE_DIR ?>" class="navbar-brand mb-0 h1">Football</a>
        <button type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                class="navbar-toggler" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <?php if (isLoggedIn()) : ?>
                    <li class="nav-item">
                        Logged in as <?= $_SESSION['user_info']['nom_uti']; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= INCLUDE_DIR ?>php/process_logout.php">Se Déconnecter</a>
                    </li>
                <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= INCLUDE_DIR ?>pages/creationUtilisateur.php">S'insrire</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= INCLUDE_DIR ?>pages/connexion.php">Se connecter</a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
</nav>