<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['uname']);
    $password = trim($_POST['pass']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT id, Nom, MDP, admin FROM NG_Pays WHERE Nom = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $username;

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $admin);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['admin'] = $admin;

                        $_SESSION['message'] = "Connexion réussie!";
                        $_SESSION['message_type'] = "success";
                        header("Location: index.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "Le mot de passe est incorrect.";
                        header("Location: login.php");
                        exit();
                    }
                }
            } else {
                $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Erreur interne lors de la vérification des informations.";
            header("Location: login.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Erreur interne lors de la connexion à la base de données.";
        header("Location: login.php");
        exit();
    }

    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <form class="form-container shadow w-450 p-3 rounded" action="login.php" method="post">
            <h4 class="text-center mb-4">Connexion</h4>
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="uname" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="uname" name="uname" placeholder="Entrez votre nom d'utilisateur" value="<?php echo isset($_SESSION['uname']) ? $_SESSION['uname'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Entrez votre mot de passe">
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">Se connecter</button>
                <a href="register.php" class="text-secondary">Créer un compte</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
