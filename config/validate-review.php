<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../employee-login.php");
    exit();}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avis_id']) && isset($_POST['action'])) {
    $avis_id = $_POST['avis_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $new_status = 'approuvé';
        $message = "Avis approuvé avec succès!";
    } elseif ($action === 'reject') {
        $new_status = 'rejeté';
        $message = "Avis rejeté.";
    } else {
        $_SESSION['error_message'] = "Action invalide.";
        header("Location: ../employee-dashboard.php");
        exit();
    }
    $sql = "UPDATE avis SET statut = ? WHERE avis_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $avis_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = $message; } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour de l'avis.";} 
    header("Location: ../employee-dashboard.php");
    exit();} else {
    $_SESSION['error_message'] = "Requête invalide.";
    header("Location: ../employee-dashboard.php");
    exit();}
?>