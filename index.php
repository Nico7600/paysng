<?php
session_start();
if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Pays - Indonesie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: url('images/cle.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .title-container {
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .dropdown-item.admin {
            color: #dc3545;
        }
        .dropdown-item.login {
            color: #007bff;
        }
        .dropdown-item.register {
            color: #28a745;
        }
        .navbar-brand span {
            color: #ffffff;
        }
        .navbar-brand span.red {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php session_start(); ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span>Indo</span><span class="red">nesie</span>
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
                                <?php if ($_SESSION['Staff_site'] == 1 || $_SESSION['Dev_site'] == 1 || $_SESSION['Admin'] == 1): ?>
                                    <a class="dropdown-item admin" href="admin.php"><i class="fas fa-user-shield"></i> Admin</a>
                                <?php endif; ?>
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
    <div class="main-container mt-5">
        <?php 
        if (!empty($message)) {
            echo '<div class="alert alert-' . $message_type . '">' . $message . '</div>';
        }
        ?>
        <div class="title-container text-center">
            <h1>Bienvenue sur le site de gestion de Indonesie Epsilon</h1>
            <h2>Crée par Nico7600</h2>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
