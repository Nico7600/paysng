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
include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- ...existing code... -->
</body>
</html>
