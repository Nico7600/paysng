<?php
session_start();

$servername = "nicolavnicolas.mysql.db";
$username = "nicolavnicolas";
$password = "Rex220405";
$dbname = "nicolavnicolas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$nom = $_SESSION['username'];
$sql = "SELECT * FROM NG_Pays WHERE Nom='$nom'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['Chef'] != 1 && $row['Officier'] != 1 && $row['Membre'] != 1 && $row['Recrut'] != 1 && $row['Admin'] != 1) {
        echo "Access denied.";
        exit();
    }
} else {
    echo "User not found.";
    exit();
}

$conn->close();
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
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .main-container {
            padding: 20px;
            margin-top: 80px; /* Increase margin-top to improve spacing */
        }
        .title-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <span style="color: red;">Indonesie</span><span style="color: white;"> privée</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="craft.php"><i class="fas fa-hammer"></i> Craft</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="craft_pays.php"><i class="fas fa-globe"></i> Craft Pays</a> <!-- Ensure this link is correct -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gestion_offi.php"><i class="fas fa-cogs"></i> Gestion Offi</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?></a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="main-container text-center">
        <div class="title-container">
            <h1>Gestion des Pays</h1>
            <h2>Indonesie Epsilon</h2>
        </div>
        <!-- Add your page content here -->
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
