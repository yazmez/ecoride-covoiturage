<?php
session_start();
require_once 'config/config.php';
if ($_POST) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Les mots de passe ne correspondent pas";
        header("Location: ../register.php");
        exit();
    }
    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "Le mot de passe doit faire au moins 8 caractères";
        header("Location: ../register.php");
        exit();
    }
    
    try { 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (pseudo, email, password, credit) VALUES (?, ?, ?, 20)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $pseudo, $email, $hashed_password);
        $stmt->execute();
        
        $_SESSION['success_message'] = "Compte créé avec succès! Vous avez 20 crédits.";
        header("Location: ../login.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur: Pseudo ou email déjà utilisé";
        header("Location: ../register.php");
        exit();
    }
}
?>