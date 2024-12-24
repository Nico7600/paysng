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

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$nom = $_SESSION['username'];

$sql = "SELECT Staff_site, Dev_site, Admin FROM NG_Pays WHERE Nom = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nom);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows <= 0) {
    echo "Nom d'utilisateur non autorisé pour cette page.";
    exit;
}

$stmt->bind_result($Staff_site, $Dev_site, $Admin);
$stmt->fetch();

if ($Staff_site != 1 && $Dev_site != 1 && $Admin != 1) {
    echo "Vous n'avez pas les droits pour accéder à cette page.";
    exit;
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_role'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $current_value = $_POST['current_value'];
    $new_value = $current_value == 1 ? 0 : 1;

    $update_sql = "UPDATE NG_Pays SET $role = ? WHERE Nom = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("is", $new_value, $username);
    if ($update_stmt->execute()) {
        echo "<script>showNotification('Role updated successfully.');</script>";
    } else {
        echo "Error updating role: " . $conn->error;
    }
    $update_stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_grade'])) {
    $username = $_POST['username'];
    $grade = $_POST['grade'];

    $update_sql = "UPDATE NG_Pays SET Grade = ? WHERE Nom = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $grade, $username);
    if ($update_stmt->execute()) {
        echo "<script>showNotification('Grade updated successfully.');</script>";
    } else {
        echo "Error updating grade: " . $conn->error;
    }
    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Admin Panel</h1>
        
        <div class="container mt-4">
            <h2>Gestion Craft Global</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $global_sql = "SELECT id, username, action, date FROM Craft_Global";
                    $global_result = $conn->query($global_sql);
                    if ($global_result->num_rows > 0) {
                        while ($row = $global_result->fetch_assoc()) {
                            echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['action']}</td><td>{$row['date']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Aucun enregistrement trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container mt-4">
            <h2>Craft Pays</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Pays</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pays_sql = "SELECT id, username, pays, action, date FROM Craft_Pays";
                    $pays_result = $conn->query($pays_sql);
                    if ($pays_result->num_rows > 0) {
                        while ($row = $pays_result->fetch_assoc()) {
                            echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['pays']}</td><td>{$row['action']}</td><td>{$row['date']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Aucun enregistrement trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container mt-4">
            <h2>Liste Utilisateur</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Staff Site</th>
                        <th>Dev Site</th>
                        <th>Admin</th>
                        <th>Gestion Pays</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users_sql = "SELECT Nom, Staff_site, Dev_site, Admin, Pays FROM NG_Pays";
                    $users_result = $conn->query($users_sql);
                    if ($users_result->num_rows > 0) {
                        while ($row = $users_result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['Nom']}</td>
                                    <td>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='role' value='Staff_site'>
                                            <input type='hidden' name='current_value' value='{$row['Staff_site']}'>
                                            <button type='submit' name='toggle_role' class='btn " . ($row['Staff_site'] ? 'btn-success' : 'btn-danger') . " btn-sm'>" . ($row['Staff_site'] ? 'Oui' : 'Non') . "</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='role' value='Dev_site'>
                                            <input type='hidden' name='current_value' value='{$row['Dev_site']}'> <!-- Assuming 'Dev_site' is a column in the database -->
                                            <button type='submit' name='toggle_role' class='btn " . ($row['Dev_site'] ? 'btn-success' : 'btn-danger') . " btn-sm'>" . ($row['Dev_site'] ? 'Oui' : 'Non') . "</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='role' value='Admin'>
                                            <input type='hidden' name='current_value' value='{$row['Admin']}'> <!-- Assuming 'Admin' is a column in the database -->
                                            <button type='submit' name='toggle_role' class='btn " . ($row['Admin'] ? 'btn-success' : 'btn-danger') . " btn-sm'>" . ($row['Admin'] ? 'Oui' : 'Non') . "</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='role' value='Pays'>
                                            <input type='hidden' name='current_value' value='{$row['Pays']}'> <!-- Assuming 'Pays' is a column in the database -->
                                            <button type='submit' name='toggle_role' class='btn " . ($row['Pays'] ? 'btn-success' : 'btn-danger') . " btn-sm'>Pays</button>
                                        </form>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='grade' value='Recrut'>
                                            <button type='submit' name='change_grade' class='btn btn-info btn-sm'>Recrut</button>
                                        </form>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='grade' value='Membre'>
                                            <button type='submit' name='change_grade' class='btn btn-warning btn-sm'>Membre</button>
                                        </form>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='grade' value='Officier'>
                                            <button type='submit' name='change_grade' class='btn btn-danger btn-sm'>Officier</button>
                                        </form>
                                        <form method='post' action='' class='d-inline'>
                                            <input type='hidden' name='username' value='{$row['Nom']}'>
                                            <input type='hidden' name='grade' value='Chef'>
                                            <button type='submit' name='change_grade' class='btn btn-primary btn-sm'>Chef</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Aucun utilisateur trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="notification" id="notification"></div>
    <script>
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
