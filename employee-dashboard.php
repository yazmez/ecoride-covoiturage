<?php 
session_start();
require_once 'config/config.php';
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee-login.php");
    exit();
}
if (isset($_SESSION['success_message'])) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 20px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo $_SESSION['success_message'];
    echo "</div>";
    unset($_SESSION['success_message']);}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Employé - EcoRide</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f0f8f0; }
        nav { background: #1f6b4e; padding: 15px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        .employee-section { background: white; padding: 20px; margin: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .review-item, .trip-item { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; background: #f9f9f9; }
        .btn-approve { background: green; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; margin-right: 5px; }
        .btn-reject { background: red; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="employee-dashboard.php">👨‍💼 Tableau de Bord</a>
        <a href="employee-logout.php">🚪 Déconnexion</a>
    </nav>

    <h1>👨‍💼 Espace Employé - EcoRide</h1>
    <p>Connecté en tant que: <strong><?php echo $_SESSION['employee_pseudo']; ?></strong></p>
    <div class="employee-section">
        <h2>📝 Avis en Attente de Validation</h2>
        <?php
        $sql = "SELECT a.avis_id, a.commentaire, a.note, u.pseudo as user_pseudo
                FROM avis a 
                JOIN depose d ON a.avis_id = d.avis_id 
                JOIN utilisateur u ON d.utilisateur_id = u.utilisateur_id
                WHERE a.statut = 'en attente'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($review = $result->fetch_assoc()) {
                echo "<div class='review-item'>";
                echo "<strong>Utilisateur:</strong> " . $review['user_pseudo'] . "<br>";
                echo "<strong>Note:</strong> " . str_repeat('⭐', $review['note']) . " ($review[note]/5)<br>";
                echo "<strong>Commentaire:</strong> " . ($review['commentaire'] ?: 'Aucun commentaire') . "<br>";
                echo "<div style='margin-top: 10px;'>";
                echo "<form action='config/validate-review.php' method='POST' style='display: inline;'>";
                echo "<input type='hidden' name='avis_id' value='$review[avis_id]'>";
                echo "<input type='hidden' name='action' value='approve'>";
                echo "<button type='submit' class='btn-approve'>✅ Approuver</button>";
                echo "</form>";
                echo "<form action='config/validate-review.php' method='POST' style='display: inline;'>";
                echo "<input type='hidden' name='avis_id' value='$review[avis_id]'>";
                echo "<input type='hidden' name='action' value='reject'>";
                echo "<button type='submit' class='btn-reject'>❌ Rejeter</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p><em>Aucun avis en attente de validation.</em></p>";
        }
        ?>
    </div>
    <div class="employee-section">
        <h2>🚗 Tous les Trajets Terminés</h2>
        <p><em>Contactez les personnes en cas de problème signalé</em></p>
        <?php
        $sql = "SELECT c.covoiturage_id, c.lieu_depart, c.lieu_arrivee, c.date_depart,
                       driver.pseudo as driver_pseudo, driver.email as driver_email
                FROM covoiturage c
                JOIN participe p ON c.covoiturage_id = p.covoiturage_id AND p.est_conducteur = 1
                JOIN utilisateur driver ON p.utilisateur_id = driver.utilisateur_id
                WHERE c.statut = 'terminé'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($trip = $result->fetch_assoc()) {
                echo "<div class='trip-item'>";
                echo "<strong>🔍 Numéro du trajet:</strong> $trip[covoiturage_id]<br>";
                echo "<strong>🚗 Conducteur:</strong> $trip[driver_pseudo] ($trip[driver_email])<br>";
                echo "<strong>📍 Trajet:</strong> $trip[lieu_depart] → $trip[lieu_arrivee]<br>";
                echo "<strong>📅 Date:</strong> $trip[date_depart]<br>";
                echo "<div style='margin-top: 10px;'>";
                echo "<button style='background: orange; color: black; padding: 5px 10px; border: none; cursor: pointer;' 
                      onclick='alert(\"Contactez: $trip[driver_pseudo] ($trip[driver_email])\")'>
                      📞 Contacter
                      </button>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p><em>Aucun trajet terminé.</em></p>";
        }
        ?>
    </div>
</body>
</html>