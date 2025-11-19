<?php 
session_start();
require_once 'config/config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - EcoRide</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f8f0;
    }
    nav {
        background: #1f6b4e;
        padding: 15px;
        text-align: center;
    }
    nav a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
    }
    .admin-section {
        background: white;
        padding: 20px;
        margin: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .stat-box {
        background: #1f6b4e;
        color: white;
        padding: 15px;
        margin: 10px;
        border-radius: 5px;
        display: inline-block;
        width: 200px;
    }
    .graph {
        background: white;
        border: 2px solid #1f6b4e;
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
    }
    .bar {
        background: #2e8b57;
        height: 25px;
        margin: 8px 0;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        display: block;
        min-width: 60px;
        text-align: right;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }
    .bar-credits {
        background: #1f6b4e;
    }
</style>
</head>
<body>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="admin-dashboard.php">👑 Admin</a>
        <a href="admin-logout.php">🚪 Déconnexion</a>
    </nav>

    <h1>👑 Espace Administrateur</h1>
    <p>Bonjour <strong><?php echo $_SESSION['admin_pseudo']; ?></strong></p>
    <div class="admin-section">
        <h2>💰 Crédits Gagnés par la Plateforme</h2>
        <?php
        $sql = "SELECT SUM(prix_personne) as total FROM covoiturage WHERE statut = 'terminé'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_credits = $row['total'] * 2; 
        echo "<div class='stat-box'><h3>Total: $total_credits crédits</h3></div>";
        ?>
    </div>
    <div class="admin-section">
        <h2>📊 Nombre de Covoiturages par Jour</h2>
        <div class="graph">
            <?php
            $sql = "SELECT DATE(date_depart) as jour, COUNT(*) as nb_trajets 
                    FROM covoiturage 
                    GROUP BY DATE(date_depart) 
                    ORDER BY jour DESC 
                    LIMIT 7";
            $result = $conn->query($sql);  
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $width = min($row['nb_trajets'] * 50, 400); 
                    echo "<div class='bar' style='width: {$width}px;'>";
                    echo $row['jour'] . " - " . $row['nb_trajets'] . " trajets";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun trajet trouvé</p>";
            }
            ?>
        </div>
    </div>
    <div class="admin-section">
        <h2>💳 Crédits Gagnés par Jour</h2>
        <div class="graph">
            <?php
            $sql = "SELECT DATE(date_depart) as jour, SUM(prix_personne) as credits 
                    FROM covoiturage 
                    WHERE statut = 'terminé'
                    GROUP BY DATE(date_depart) 
                    ORDER BY jour DESC 
                    LIMIT 7";
            $result = $conn->query($sql);  
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $credits = $row['credits'] * 2;
                    $width = min($credits * 3, 400);
                    echo "<div class='bar' style='width: {$width}px; background: #1f6b4e;'>";
                    echo $row['jour'] . " - " . $credits . " crédits";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun crédit gagné</p>";
            }
            ?>
        </div>
    </div>
    <div class="admin-section">
        <h2>👨‍💼 Créer un Employé</h2>
        <form action="config/create-employee.php" method="POST">
            <p>Nom d'utilisateur:</p>
            <input type="text" name="pseudo" required>
            
            <p>Email:</p>
            <input type="email" name="email" required>
            
            <p>Mot de passe:</p>
            <input type="password" name="password" required>
            
            <br>
            <button type="submit">Créer Employé</button>
        </form>
    </div>
    <div class="admin-section">
        <h2>⏸️ Suspendre/Réactiver des Comptes</h2>
        
        <h3>Utilisateurs:</h3>
        <?php
        $sql = "SELECT utilisateur_id, pseudo, email, suspended FROM utilisateur LIMIT 10";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($user = $result->fetch_assoc()) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px 0;'>";
                echo "<strong>$user[pseudo]</strong> - $user[email]";
                if ($user['suspended']) {
                    echo " <span style='color: red;'>(SUSPENDU)</span>";
                    echo "<form action='config/suspend-account.php' method='POST' style='display: inline;'>";
                    echo "<input type='hidden' name='user_id' value='$user[utilisateur_id]'>";
                    echo "<input type='hidden' name='type' value='utilisateur'>";
                    echo "<input type='hidden' name='action' value='unsuspend'>";
                    echo "<button type='submit' style='background: green; color: white; border: none; padding: 5px; margin-left: 10px;'>Activer</button>";
                    echo "</form>";
                } else {
                    echo "<form action='config/suspend-account.php' method='POST' style='display: inline;'>";
                    echo "<input type='hidden' name='user_id' value='$user[utilisateur_id]'>";
                    echo "<input type='hidden' name='type' value='utilisateur'>";
                    echo "<input type='hidden' name='action' value='suspend'>";
                    echo "<button type='submit' style='background: red; color: white; border: none; padding: 5px; margin-left: 10px;'>Suspendre</button>";
                    echo "</form>";
                }
                echo "</div>";
            }}
        ?>
        <h3>Employés:</h3>
        <?php
        $sql = "SELECT id, pseudo, email, suspended FROM employe";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($emp = $result->fetch_assoc()) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px 0;'>";
                echo "<strong>$emp[pseudo]</strong> - $emp[email]";
                
                if ($emp['suspended']) {
                    echo " <span style='color: red;'>(SUSPENDU)</span>";
                    echo "<form action='config/suspend-account.php' method='POST' style='display: inline;'>";
                    echo "<input type='hidden' name='emp_id' value='$emp[id]'>";
                    echo "<input type='hidden' name='type' value='employe'>";
                    echo "<input type='hidden' name='action' value='unsuspend'>";
                    echo "<button type='submit' style='background: green; color: white; border: none; padding: 5px; margin-left: 10px;'>Activer</button>";
                    echo "</form>";
                } else {
                    echo "<form action='config/suspend-account.php' method='POST' style='display: inline;'>";
                    echo "<input type='hidden' name='emp_id' value='$emp[id]'>";
                    echo "<input type='hidden' name='type' value='employe'>";
                    echo "<input type='hidden' name='action' value='suspend'>";
                    echo "<button type='submit' style='background: red; color: white; border: none; padding: 5px; margin-left: 10px;'>Suspendre</button>";
                    echo "</form>";}
                echo "</div>";}}
        ?>
    </div>
</body>
</html>