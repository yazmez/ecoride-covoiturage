<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();}
$pseudo = $_POST['pseudo'];
$email = $_POST['email'];
$password = $_POST['password'];
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO employe (pseudo, mot_de_passe, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $pseudo, $hash, $email);
if ($stmt->execute()) {
    $_SESSION['success_message'] = "✅ Employé $pseudo créé avec succès!";
} else {
    $_SESSION['error_message'] = "❌ Erreur: Impossible de créer l'employé";}
header("Location: ../admin-dashboard.php");
exit();
?>