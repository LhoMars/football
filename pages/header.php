<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= INCLUDE_DIR ?>">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
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