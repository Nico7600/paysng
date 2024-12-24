<?php
session_start();
require_once 'config.php'; 

// Use environment variables for database connection
putenv('DB_HOST=nicolavnicolas.mysql.db');
putenv('DB_NAME=nicolavnicolas');
putenv('DB_USER=nicolavnicolas');
putenv('DB_PASSWORD=Rex220405');

$mysqli = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME'));

// Check connection
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$userName = null;
$isPrime = false;

if (isset($_SESSION['id'])) {
    $sql = 'SELECT fname, is_prime FROM users WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die('Prepare Error: ' . $mysqli->error);
    }
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user) {
        $userName = $user['fname'];
        $isPrime = (bool)$user['is_prime'];
    }
    $stmt->close();
} else {
    $_SESSION['erreur'] = "Vous devez être connecté pour accéder à cette page";
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $message = $_POST['update'];
    $userId = $_SESSION['id'];
    $sql = 'INSERT INTO NG_Update (user_id, message, created_at, vote) VALUES (?, ?, NOW(), 0)';
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die('Prepare Error: ' . $mysqli->error);
    }
    $stmt->bind_param('is', $userId, $message);
    $stmt->execute();
    $stmt->close();
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    $vote = $_POST['vote'];
    $updateId = $_POST['update_id'];
    $userId = $_SESSION['id'];

    // Vérifier si l'utilisateur a déjà voté pour cette mise à jour
    $sql = 'SELECT vote FROM NG_Update WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die('Prepare Error: ' . $mysqli->error);
    }
    $stmt->bind_param('i', $updateId);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingVote = $result->fetch_assoc()['vote'] ?? null;
    $stmt->close();

    if ($existingVote === null) {
        // Si pas encore voté, mettre à jour le compteur de votes
        $sql = 'UPDATE NG_Update SET vote = vote + ? WHERE id = ?';
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare Error: ' . $mysqli->error);
        }
        $stmt->bind_param('ii', $vote, $updateId);
        $stmt->execute();
        $stmt->close();
    } elseif ($existingVote != $vote) {
        // Mettre à jour le vote si l'utilisateur change d'avis
        $sql = 'UPDATE NG_Update SET vote = vote + ? - ? WHERE id = ?';
        $stmt = $mysqli->prepare($sql);
        if ($stmt === false) {
            die('Prepare Error: ' . $mysqli->error);
        }
        $stmt->bind_param('iii', $vote, $existingVote, $updateId);
        $stmt->execute();
        $stmt->close();
    }
}

// Récupération des patch notes
$sql = 'SELECT NG_Update.id, NG_Update.message, NG_Update.created_at, users.username, NG_Update.vote 
        FROM NG_Update 
        JOIN users ON NG_Update.user_id = users.id 
        ORDER BY NG_Update.created_at DESC';
$result = $mysqli->query($sql);
if ($result === false) {
    die('Query Error: ' . $mysqli->error);
}
$patchNotes = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patch Notes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <a href="index.php" class="home-button"><i class="fas fa-home"></i></a>
    <div class="container mt-5">
        <h1 class="centered-bold">Patch Notes</h1>
        <div class="patch-notes">
            <?php foreach ($patchNotes as $note): ?>
                <div class="card mb-3 patch-note-container">
                    <div class="card-body">
                        <div class="formatted-text">
                            <?php
                            $lines = explode("\n", htmlspecialchars($note['message']));
                            foreach ($lines as $line) {
                                if (strpos($line, '#') === 0) {
                                    echo '<p class="highlight">' . substr($line, 1) . '</p>';
                                } else {
                                    echo '<ul><li>' . $line . '</li></ul>';
                                }
                            }
                            ?>
                        </div>
                        <div class="developer">Développeur : <?php echo htmlspecialchars($note['username']); ?></div>
                        <div class="current-date">Posté le <?php echo htmlspecialchars(date('d/m/Y', strtotime($note['created_at']))); ?> à <?php echo htmlspecialchars(date('H:i:s', strtotime($note['created_at']))); ?></div>
                        <div class="vote-buttons">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="update_id" value="<?php echo $note['id']; ?>">
                                <button type="submit" name="vote" value="1" class="btn btn-success"><i class="fas fa-arrow-up"></i></button>
                            </form>
                            <span>Votes: <?php echo $note['vote']; ?></span>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="update_id" value="<?php echo $note['id']; ?>">
                                <button type="submit" name="vote" value="-1" class="btn btn-danger"><i class="fas fa-arrow-down"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>