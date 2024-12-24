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
    if ($row['Staff_site'] != 1 && $row['Dev_site'] != 1 && $row['Admin'] != 1) {
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
    <title>Modifier Produit - Indonesie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="main-container text-center">
        <div class="title-container">
            <h1>Modifier Produit</h1>
        </div>
        <?php
        // Database connection details
        $servername = "nicolavnicolas.mysql.db";
        $username = "nicolavnicolas";
        $password = "Rex220405";
        $dbname = "nicolavnicolas";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("<div class='alert alert-danger'>Erreur de connexion à la base de données: " . mysqli_connect_error() . "</div>");
        }

        // Fetch all products
        $products = [];
        $sql = "SELECT * FROM NG_Craft";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        } else {
            echo "<div class='alert alert-danger'>Erreur de récupération des produits: " . mysqli_error($conn) . "</div>";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $se_craft = mysqli_real_escape_string($conn, $_POST['se_craft']);

            $photo = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES['photo']['name']);
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                    $photo = $target_file;
                } else {
                    echo "<div class='alert alert-danger'>Erreur lors du téléchargement de la photo.</div>";
                }
            }

            if ($photo) {
                $sql = "UPDATE NG_Craft SET title='$name', description='$description', craft_type='$se_craft', image='$photo' WHERE id='$id'";
            } else {
                $sql = "UPDATE NG_Craft SET title='$name', description='$description', craft_type='$se_craft' WHERE id='$id'";
            }

            if (mysqli_query($conn, $sql)) {
                sleep(1);
                header("Location: edit.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Erreur de mise à jour : " . mysqli_error($conn) . "</div>";
            }
        }
        ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['title']; ?></h5>
                            <button class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#editModal"
                                data-id="<?php echo $product['id']; ?>"
                                data-title="<?php echo $product['title']; ?>"
                                data-description="<?php echo $product['description']; ?>"
                                data-craft_type="<?php echo $product['craft_type']; ?>"
                                data-photo="<?php echo $product['image']; ?>">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modifier Produit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="edit.php" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="product-id">
                            <div class="form-group">
                                <label for="name">Nom du produit</label>
                                <input type="text" class="form-control" id="product-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="product-description" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="se_craft">Se Craft</label>
                                <select class="form-control" id="product-se_craft" name="se_craft" required>
                                    <option value="Table de craft">Table de craft</option>
                                    <option value="Station de craft">Station de craft</option>
                                    <option value="Compresseur électrique">Compresseur électrique</option>
                                    <option value="Caochouteuse">Caochouteuse</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" class="form-control" id="product-photo" name="photo">
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var title = button.data('title');
            var description = button.data('description');
            var craft_type = button.data('craft_type');
            var photo = button.data('photo');

            var modal = $(this);
            modal.find('#product-id').val(id);
            modal.find('#product-name').val(title);
            modal.find('#product-description').val(description);
            modal.find('#product-se_craft').val(craft_type);
            // modal.find('#product-photo').val(photo);

            // Modifier le titre de la page
            document.title = "Modifier Produit - " + title;
        });
    </script>
</body>

</html>