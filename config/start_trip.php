<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../login.php");
    exit();}
$trip_id = $_POST['trip_id'];
$user_id = $_SESSION['utilisateur_id'];
$check_sql = "SELECT est_conducteur FROM participe WHERE covoiturage_id = ? AND utilisateur_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $trip_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    
    if ($data['est_conducteur']) {
        $update_sql = "UPDATE covoiturage SET statut = 'en_cours' WHERE covoiturage_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $trip_id);
        $update_stmt->execute();
        
        $_SESSION['success_message'] = "🚗 Trajet démarré! Bon voyage!";
    } else {
        $_SESSION['error_message'] = "Seul le conducteur peut démarrer le trajet";
    }
} else {
    $_SESSION['error_message'] = "Trajet non trouvé";}
header("Location: ../user-space.php");
exit();
?>