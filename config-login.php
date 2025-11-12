<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    
    $_SESSION['user'] = $username;
    $_SESSION['message'] = "Connexion réussie!";
    header("Location: user-space.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>