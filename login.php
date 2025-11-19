<?php session_start();
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT utilisateur_id, password FROM utilisateur WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            $_SESSION['utilisateur_id'] = $user['utilisateur_id'];
            $_SESSION['user'] = $username;
            $_SESSION['success_message'] = "Connexion réussie!";
            header("Location: user-space.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Mot de passe incorrect.";
        }
    } else {
        $_SESSION['error_message'] = "Utilisateur non trouvé.";
    }
}

if (isset($_SESSION['error_message']) && $_SESSION['error_message'] == "Utilisateur non trouvé.") {
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EcoRide</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8f0;
            background-image: url(https://media.gettyimages.com/id/1771118509/fr/photo/electric-car-charging-at-the-electric-station-on-the-street.jpg?s=2048x2048&w=gi&k=20&c=tdkVvchnrWldjViszRTD4pXBc7jBU82vGD2dLYtIIz0=);
        }
        nav {
            background: #1f6b4e;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }
        .login-form {
            background: #2e8b57;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            max-width: 400px;
        }
        .login-form input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-form button {
            padding: 10px 20px;
            background: #1f6b4e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .register-link {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="covoiturages.php">🚗 Covoiturages</a>
        <a href="login.php">🔐 Connexion</a>
        <a href="contact.php">📞 Contact</a>
        <a href="user-space.php">👤 Mon Espace</a> 
    </nav>

    <h1>Connexion à EcoRide</h1>
    <form class="login-form" action="login.php" method="POST" autocomplete="off">
        <h2>Se Connecter</h2>
        <label for="username">Nom d'utilisateur:</label><br>
        <input type="text" id="username" name="username"><br>
        
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password"><br>
        
        <button type="submit">Se connecter</button>
    </form>

    <div class="register-link">
    <?php if (isset($_SESSION['user'])): ?>
        <p>✅ Déjà connecté en tant que <strong><?php echo $_SESSION['user']; ?></strong></p>
        <p><a href="logout.php" style="color: red;"> Se déconnecter</a></p>
    <?php else: ?>
        <p>Pas de compte? <a href="register.php">Créer un compte</a></p>
    <?php endif; ?>
</div>
</body>
</html>