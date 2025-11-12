<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'EcoRide');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo'])) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO utilisateur (pseudo, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $pseudo, $email, $password);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Compte créé avec succès! 20 crédits offerts!";
        header("Location: login.php");
        exit();
    } else {$_SESSION['error'] = "Erreur: " . $conn->error;
        header("Location: register.php");
        exit();}}
?>