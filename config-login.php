<?php
session_start();
require_once 'config.php';

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
        
    
        if (password_verify($password, $user['password'])) {

            $_SESSION['utilisateur_id'] = $user['utilisateur_id'];
            $_SESSION['username'] = $username;
            $_SESSION['success_message'] = "Connexion réussie!";
            header("Location: user-space.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Mot de passe incorrect.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Utilisateur non trouvé.";
        header("Location: login.php");
        exit(); }} else { $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
    header("Location: login.php");
    exit();}
?>