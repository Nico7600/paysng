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

$title = $_POST['title'];
$image = $_FILES['image']['name'];
$description = $_POST['description'];
$craft_type = $_POST['craft_type'];
$prerequisites = $_POST['prerequisites'];

// Ensure the images directory exists
$target_dir = "images/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($image);

// Check if file upload is successful
if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO NG_Craft (title, image, description, craft_type, prerequisites) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $target_file, $description, $craft_type, $prerequisites);
    $stmt->execute();
    $stmt->close();
} else {
    echo "Sorry, there was an error uploading your file.";
}

$conn->close();

header("Location: craft.php");
exit();
?>

<!-- Copyright notice -->
<footer>
    <p>&copy; 2023 Indonesie. All rights reserved.</p>
</footer>
