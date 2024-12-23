<?php
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

// Check if the username already exists
$sql = "SELECT * FROM NG_Pays WHERE Nom='$nom'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Username already exists. Please choose a different username.";
} else {
    $sql = "INSERT INTO NG_Pays (Nom, MDP) VALUES ('$nom', '$mdp')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
