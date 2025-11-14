<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_POST) {
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $date_time = $_POST['date_time'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $vehicle_id = $_POST['vehicle_id'];
    $preferences = $_POST['preferences'];
    $user_id = $_SESSION['user_id'];

    $date_depart = date('Y-m-d', strtotime($date_time));
    $heure_depart = date('H:i:s', strtotime($date_time));
    
    try {
        $sql_ride = "INSERT INTO covoiturage (lieu_depart, lieu_arrivee, date_depart, heure_depart, nb_place, prix_personne, statut) 
                    VALUES (?, ?, ?, ?, ?, ?, 'active')";
        
        $stmt_ride = $conn->prepare($sql_ride);
        $stmt_ride->bind_param("ssssid", $departure, $arrival, $date_depart, $heure_depart, $seats, $price);
        $stmt_ride->execute();
        $ride_id = $stmt_ride->insert_id;
        $sql_link = "INSERT INTO utilise (voiture_id, covoiturage_id) VALUES (?, ?)";
        $stmt_link = $conn->prepare($sql_link);
        $stmt_link->bind_param("ii", $vehicle_id, $ride_id);
        $stmt_link->execute();
        
        $_SESSION['success_message'] = "✅ Covoiturage créé avec succès!";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur: " . $e->getMessage();
    }
    
    header("Location: ../user-space.php");
    exit();
}
?>