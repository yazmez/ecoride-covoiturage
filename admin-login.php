<?php 
session_start();
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
require_once 'config/config.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: admin-dashboard.php');
    exit();}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($admin && password_verify($password, $admin['mot_de_passe'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_pseudo'] = $admin['pseudo'];
            $_SESSION['admin_role'] = 'admin';
            $_SESSION['success_message'] = "Connexion administrateur réussie!";
            header("Location: admin-dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Mot de passe incorrect.";
        }
    } else {
        $_SESSION['error_message'] = "Administrateur non trouvé.";}}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - EcoRide</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8f0;
            background-image: url(https://media.gettyimages.com/id/2220409042/fr/photo/african-american-male-software-engineer-developer-use-computer-work-on-program-coding-and.jpg?s=2048x2048&w=gi&k=20&c=Nk35CqwXaWEb2NZL_4wQGe7P7J6_FJMYiKgz9Nan-mA=);
            background-size: cover;}
        nav {
            background: #1f6b4e;
            padding: 15px;
            text-align: center;}
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;}
        .login-form {
            background: #2e8b57;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            max-width: 400px;
        }
        .admin-notice {
            background: #fff3cd;
            border: 2px solid #856404;
            padding: 15px;
            margin: 20px;
            border-radius: 8px;
            text-align: center;
            color: #856404;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="admin-login.php">👑 Connexion Admin</a>
    </nav>

    <div class="admin-notice">
        <h2>👑 Espace Administrateur EcoRide</h2>
        <p>Accès réservé aux administrateurs système</p>
    </div>

    <h1>Connexion Administrateur</h1>
    <form class="login-form" action="admin-login.php" method="POST" autocomplete="off">
        <h2>Se Connecter (Admin)</h2>
        <label for="username">Nom d'administrateur:</label><br>
        <input type="text" id="username" name="username" required><br>
        
        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br>
        
        <button type="submit">Se connecter</button>
    </form>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 5px;'>
            <?php echo $_SESSION['error_message']; ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
</body>
</html>