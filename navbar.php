<?php session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <span style="color: white;">Indo</span><span style="color: red;">nesie</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="http://indo.nicolasdeprets.online/pays"><i class="fas fa-flag"></i> Pays</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://indo.nicolasdeprets.online/craft"><i class="fas fa-hammer"></i> Craft</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://indo.nicolasdeprets.online/patchnote"><i class="fas fa-file-alt"></i> Patchnote</a>
                </li>
                <li class="nav-item dropdown">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item admin" href="admin.php" style="color: blue;"><i class="fas fa-user-shield"></i> Admin</a>
                            <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    <?php else: ?>
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> Login
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item login" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                            <a class="dropdown-item register" href="register.php"><i class="fas fa-user-plus"></i> Crée un compte</a>
                        </div>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
