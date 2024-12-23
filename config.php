<?php
// Informations de connexion à la base de données
define('DB_SERVER', 'nicolavnicolas.mysql.db');
define('DB_USERNAME', 'nicolavnicolas');
define('DB_PASSWORD', 'Rex220405');
define('DB_NAME', 'nicolavnicolas');

// Connexion à la base de données MySQL
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérifier la connexion
if ($mysqli === false) {
    die("ERREUR : Impossible de se connecter. " . $mysqli->connect_error);
}
?>
