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

$nom = $_POST['nom'];
$mdp = $_POST['mdp'];

$sql = "SELECT * FROM NG_Pays WHERE Nom='$nom' AND MDP='$mdp'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['nom'] = $nom;
    echo "Login successful";
    session_write_close(); // Ensure session data is saved
    header("Location: index.php");
    exit();
} else {
    echo "Invalid credentials";
}

$conn->close();
?>
