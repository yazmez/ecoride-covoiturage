<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_POST) {
    $plaque = $_POST['plaque'];
    $date_immat = $_POST['date_immat'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $couleur = $_POST['couleur'];
    $places = $_POST['places'];
    $energie = $_POST['energie'];
    $user_id = $_SESSION['user_id'];
    
    try {
        $sql_vehicle = "INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation) 
                       VALUES (?, ?, ?, ?, ?)";
        
        $stmt_vehicle = $conn->prepare($sql_vehicle);
        $stmt_vehicle->bind_param("sssss", $modele, $plaque, $energie, $couleur, $date_immat);
        $stmt_vehicle->execute();
        $vehicle_id = $stmt_vehicle->insert_id;
        $sql_link = "INSERT INTO gere (utilisateur_id, voiture_id) VALUES (?, ?)";
        $stmt_link = $conn->prepare($sql_link);
        $stmt_link->bind_param("ii", $user_id, $vehicle_id);
        $stmt_link->execute();
        
        $_SESSION['success_message'] = "✅ Véhicule ajouté avec succès!";
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur: " . $e->getMessage();
    }
    
    header("Location: ../user-space.php");
    exit();
}
?>