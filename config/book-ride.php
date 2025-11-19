<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_POST && isset($_POST['ride_id'])) {
    require_once 'config.php';
    
    $user_id = $_SESSION['utilisateur_id'];
    $ride_id = $_POST['ride_id'];
    
    try {
        $sql_booking = "INSERT INTO participe (utilisateur_id, covoiturage_id) VALUES (?, ?)";
        $stmt_booking = $conn->prepare($sql_booking);
        $stmt_booking->bind_param("ii", $user_id, $ride_id);
        
        if ($stmt_booking->execute()) {
            $_SESSION['success_message'] = "✅ Réservation confirmée! Trajet réservé avec succès.";
        } else {
            $_SESSION['error_message'] = "❌ Erreur de base de données.";
        }
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "❌ Erreur: " . $e->getMessage();
    }
    
    header("Location: ../vue-detaillee.php");
    exit();
} else {
    $_SESSION['error_message'] = "❌ Données manquantes.";
    header("Location: ../vue-detaillee.php");
    exit();
}
?>