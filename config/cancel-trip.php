<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../login.php");
    exit();}
$trip_id = $_POST['trip_id'];
$user_id = $_SESSION['utilisateur_id'];
$sql = "SELECT c.prix_personne, p.est_conducteur 
        FROM covoiturage c 
        JOIN participe p ON c.covoiturage_id = p.covoiturage_id 
        WHERE c.covoiturage_id = ? AND p.utilisateur_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $trip_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $price = $data['prix_personne'];
    $is_driver = $data['est_conducteur'];
    
    if ($is_driver) {
        $update_sql = "UPDATE covoiturage SET statut = 'annulé' WHERE covoiturage_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $trip_id);
        $update_stmt->execute();
        
        $_SESSION['success_message'] = "Trajet annulé avec succès";} else {
        $refund_sql = "UPDATE utilisateur SET credit = credit + ? WHERE utilisateur_id = ?";
        $refund_stmt = $conn->prepare($refund_sql);
        $refund_stmt->bind_param("ii", $price, $user_id);
        $refund_stmt->execute();
        $delete_sql = "DELETE FROM participe WHERE covoiturage_id = ? AND utilisateur_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $trip_id, $user_id);
        $delete_stmt->execute();
        $seat_sql = "UPDATE covoiturage SET nb_place = nb_place + 1 WHERE covoiturage_id = ?";
        $seat_stmt = $conn->prepare($seat_sql);
        $seat_stmt->bind_param("i", $trip_id);
        $seat_stmt->execute();
        
        $_SESSION['success_message'] = "Participation annulée - " . $price . " crédits remboursés";}
} else {
    $_SESSION['error_message'] = "Erreur: Trajet non trouvé";}
header("Location: ../user-space.php");
exit();
?>