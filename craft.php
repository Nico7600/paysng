<?php
$servername = "nicolavnicolas.mysql.db";
$username = "nicolavnicolas";
$password = "Rex220405";
$dbname = "nicolavnicolas";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for dark mode preference in cookies
$dark_mode = isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] == '1';

// Fetch craft items from the database
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT id, title, image, description, craft_type, prerequisites FROM NG_Craft WHERE title LIKE '%$search%'";
$result = $conn->query($sql);

$crafts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $crafts[] = $row;
    }
}

$conn->close();
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Craft - Indonesie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            padding-top: 100px;
            background-image: url('images/fond craft.jpg');
            background-attachment: fixed;
            background-size: cover;
            font-family: 'Roboto', sans-serif;
            <?php if ($dark_mode): ?>
            background-color: #121212;
            color: #ffffff;
            <?php endif; ?>
        }
        .card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
            background-color: #ffffff;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            <?php if ($dark_mode): ?>
            background-color: #1e1e1e;
            color: #ffffff;
            <?php endif; ?>
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-title {
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            text-align: center;
            color: #343a40;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: contain;
        }
        .modal-header {
            background-color: #f8f9fa;
            text-align: center;
            <?php if ($dark_mode): ?>
            background-color: #1e1e1e;
            color: #ffffff;
            <?php endif; ?>
        }
        .modal-title {
            font-weight: bold;
            width: 100%;
        }
        .modal-body {
            white-space: nowrap;
            <?php if ($dark_mode): ?>
            background-color: #1e1e1e;
            color: #ffffff;
            <?php endif; ?>
        }
        .modal-footer {
            <?php if ($dark_mode): ?>
            background-color: #1e1e1e;
            <?php endif; ?>
        }
        .modal-body p {
            margin-bottom: 1rem;
            font-family: 'Roboto', sans-serif;
            font-size: 1rem;
        }
        .modal-body img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }
        .logo {
            color: red;
            font-size: 24px;
            font-weight: bold;
        }
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 70px; /* Adjusted to avoid overlapping with the navbar */
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 10px;
            background-color: transparent; /* Removed white area */
            <?php if ($dark_mode): ?>
            background-color: transparent;
            <?php endif; ?>
        }
        .search-container form {
            display: flex;
            align-items: center;
        }
        .highlight {
            font-weight: bold;
        }
        .btn-close {
            background-color: red;
            color: white;
            <?php if ($dark_mode): ?>
            background-color: #ff4d4d;
            <?php endif; ?>
        }
        .btn-outline-success {
            border-color: #28a745;
            color: #28a745;
        }
        .btn-outline-success:hover {
            background-color: #28a745;
            color: #ffffff;
        }
        .craft-type-brown {
            color: #8B4513;
            font-weight: bold;
        }
        .craft-type-red {
            color: red;
            font-weight: bold;
        }
        .home-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4682B4;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            text-decoration: none;
            <?php if ($dark_mode): ?>
            background-color: #1e1e1e;
            <?php endif; ?>
        }
        .home-button i {
            color: white;
        }
        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #ffffff;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="search-container">
        <form class="form-inline" method="get" action="craft.php">
            <input class="form-control mr-sm-2" type="search" placeholder="Recherche" aria-label="Recherche" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <button id="darkModeToggle" class="btn btn-outline-secondary ml-2"><?php echo $dark_mode ? 'Mode Clair' : 'Mode Sombre'; ?></button>
    </div>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div style="height: 120px;"></div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($crafts as $craft): ?>
            <div class="col-md-3 mb-4">
                <div class="card" data-toggle="modal" data-target="#productModal<?php echo $craft['id']; ?>">
                    <img src="<?php echo $craft['image']; ?>" class="card-img-top" alt="<?php echo $craft['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $craft['title']; ?></h5>
                        <p class="card-text"><strong>Se craft :</strong> <span class="<?php echo $craft['craft_type'] == 'Table de craft' ? 'craft-type-brown' : 'craft-type-red'; ?>"><?php echo $craft['craft_type']; ?></span></p>
                        <p class="card-text"><strong>Prérequis :</strong><br><?php echo nl2br($craft['prerequisites']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modals -->
    <?php foreach ($crafts as $craft): ?>
    <div class="modal fade" id="productModal<?php echo $craft['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $craft['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel<?php echo $craft['id']; ?>">
                        <span class="logo">•</span> <?php echo $craft['title']; ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="<?php echo $craft['image']; ?>" class="img-fluid mb-3" alt="<?php echo $craft['title']; ?>">
                    <p><?php echo nl2br(str_replace('-', "\n-", $craft['description'])); ?></p>
                    <p><strong class="highlight">Se craft :</strong> <span class="<?php echo $craft['craft_type'] == 'Table de craft' ? 'craft-type-brown' : 'craft-type-red'; ?>"><?php echo $craft['craft_type']; ?></span></p>
                    <p><strong>Prérequis :</strong><br><?php echo nl2br($craft['prerequisites']); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    <div class="modal fade" id="editModal<?php echo $craft['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $craft['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="edit_craft.php">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?php echo $craft['id']; ?>">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $craft['title']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="text" class="form-control" id="image" name="image" value="<?php echo $craft['image']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"><?php echo $craft['description']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="craft_type">Craft Type</label>
                            <input type="text" class="form-control" id="craft_type" name="craft_type" value="<?php echo $craft['craft_type']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="prerequisites">Prérequis</label>
                            <textarea class="form-control" id="prerequisites" name="prerequisites"><?php echo $craft['prerequisites']; ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <a href="index.php" class="home-button">
        <i class="fa-solid fa-house"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            var darkMode = <?php echo $dark_mode ? 'false' : 'true'; ?>;
            document.cookie = "dark_mode=" + (darkMode ? '1' : '0') + "; path=/";
            location.reload();
        });
    </script>
</body>
</html>

<!-- Copyright notice -->
<footer>
    <p>&copy; 2023 Indonesie. By:Nico7600.</p>
</footer>