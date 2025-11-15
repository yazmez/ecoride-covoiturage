<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: ../login.php");
    exit();}
$trip_id = $_POST['trip_id'];
$user_id = $_SESSION['utilisateur_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];
$check_sql = "SELECT p.est_conducteur, c.statut 
              FROM participe p 
              JOIN covoiturage c ON p.covoiturage_id = c.covoiturage_id 
              WHERE p.covoiturage_id = ? AND p.utilisateur_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $trip_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();  
    if (!$data['est_conducteur'] && $data['statut'] == 'terminé') {
        $insert_sql = "INSERT INTO avis (commentaire, note, statut) VALUES (?, ?, 'en_attente')";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("si", $comment, $rating);
        $insert_stmt->execute();
        $review_id = $insert_stmt->insert_id;
        $link_sql = "INSERT INTO depose (utilisateur_id, avis_id) VALUES (?, ?)";
        $link_stmt = $conn->prepare($link_sql);
        $link_stmt->bind_param("ii", $user_id, $review_id);
        $link_stmt->execute();
        $_SESSION['success_message'] = "✅ Avis envoyé! En attente de validation.";
    } else {
        $_SESSION['error_message'] = "Vous ne pouvez pas noter ce trajet";
    }} else {
    $_SESSION['error_message'] = "Trajet non trouvé";}
header("Location: ../user-space.php");
exit();
?>