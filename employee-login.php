<?php 
session_start();
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
require_once 'config/config.php';
if (isset($_SESSION['employee_id'])) {
    header('Location: employee-dashboard.php');
    exit();}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM employe WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
    $employee = $result->fetch_assoc();
    if ($employee && isset($employee['mot_de_passe']) && password_verify($password, $employee['mot_de_passe'])) {
            $_SESSION['employee_id'] = $employee['id'];
            $_SESSION['employee_pseudo'] = $employee['pseudo'];
            $_SESSION['employee_role'] = 'employe';
            $_SESSION['success_message'] = "Connexion employé réussie!";
            header("Location: employee-dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Mot de passe incorrect.";
        }
    } else {
        $_SESSION['error_message'] = "Employé non trouvé.";
    }}
if (isset($_SESSION['error_message']) && $_SESSION['error_message'] == "Employé non trouvé.") {
    unset($_SESSION['error_message']);}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Employé - EcoRide</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8f0;
            background-image: url(https://media.gettyimages.com/id/2186780905/fr/photo/young-asian-man-working-as-software-developer-coding-on-computer-in-modern-office.jpg?s=2048x2048&w=gi&k=20&c=FxxYDYv3bdDbwfng4I9wWjlG8DkLUohh97EB2vjgRBM=);
            background-size: cover;}
        nav {
            background: #1f6b4e;
            padding: 15px;
            text-align: center; }
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
            max-width: 400px;}
        .login-form input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;}
        .login-form button {
            padding: 10px 20px;
            background: #1f6b4e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;}
        .employee-notice {
            background: #e7f3ff;
            border: 2px solid #1f6b4e;
            padding: 15px;
            margin: 20px;
            border-radius: 8px;
            text-align: center;}
    </style>
</head>
<body>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="covoiturages.php">🚗 Covoiturages</a>
        <a href="login.php">🔐 Connexion Utilisateur</a>
        <a href="employee-login.php">👨‍💼 Connexion Employé</a>
        <a href="contact.php">📞 Contact</a>
    </nav>

    <div class="employee-notice">
        <h2>👨‍💼 Espace Employé EcoRide</h2>
        <p>Accès réservé au personnel autorisé</p>
    </div>

    <h1>Connexion Employé</h1>
    <form class="login-form" action="employee-login.php" method="POST" autocomplete="off">
        <h2>Se Connecter (Employé)</h2>
        <label for="username">Nom d'utilisateur employé:</label><br>
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