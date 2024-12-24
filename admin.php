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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $staff_site = isset($_POST['staff_site']) ? 1 : 0;
    $dev_site = isset($_POST['dev_site']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;

    $update_sql = "UPDATE NG_Pays SET Staff_site = ?, Dev_site = ?, Admin = ? WHERE Nom = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iiis", $staff_site, $dev_site, $admin, $username);
    if ($update_stmt->execute()) {
        echo "Roles updated successfully.";
    } else {
        echo "Error updating roles: " . $conn->error;
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
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Admin Panel</h1>
        <!-- ...existing code... -->
        <h2>Gérer les rôles des utilisateurs</h2>
        <form method="post" action="">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <input type="checkbox" id="staff_site" name="staff_site">
            <label for="staff_site">Staff Site</label>
            <br>
            <input type="checkbox" id="dev_site" name="dev_site">
            <label for="dev_site">Dev Site</label>
            <br>
            <input type="checkbox" id="admin" name="admin">
            <label for="admin">Admin</label>
            <br>
            <input type="submit" value="Mettre à jour les rôles">
        </form>

        <h2>Gestion Craft Global</h2>
        <table>
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

        <h2>Craft Pays</h2>
        <table>
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

        <h2>Liste Utilisateur</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Staff Site</th>
                    <th>Dev Site</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users_sql = "SELECT Nom, Staff_site, Dev_site, Admin FROM NG_Pays";
                $users_result = $conn->query($users_sql);
                if ($users_result->num_rows > 0) {
                    while ($row = $users_result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['Nom']}</td>
                                <td>" . ($row['Staff_site'] ? 'Oui' : 'Non') . "</td>
                                <td>" . ($row['Dev_site'] ? 'Oui' : 'Non') . "</td>
                                <td>" . ($row['Admin'] ? 'Oui' : 'Non') . "</td>
                                <td>
                                    <button class='btn btn-success btn-sm'>Recrut</button>
                                    <button class='btn btn-info btn-sm'>Membre</button>
                                    <button class='btn btn-warning btn-sm'>Officier</button>
                                    <button class='btn btn-danger btn-sm'>Chef</button>
                                    <button class='btn btn-primary btn-sm'>Staff Site</button>
                                    <button class='btn btn-secondary btn-sm'>Dev Site</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Aucun utilisateur trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <!-- ...existing code... -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
