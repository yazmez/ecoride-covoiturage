<?php session_start(); ?>
<?php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['success_message'])) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 20px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo $_SESSION['success_message'];
    echo "</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo $_SESSION['error_message'];
    echo "</div>";
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur - EcoRide</title>
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
        .user-section {
            background: white;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .vehicle-form input, .vehicle-form select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .vehicle-form button, .preferences button {
            padding: 10px 20px;
            background: #2e8b57;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            background: #2e8b57;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        .credits-badge {
            background: gold;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<?php
require_once 'config/config.php';
?>
    <h1>Bienvenue, <?php echo $_SESSION['user']; ?> ! 🌱</h1>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="covoiturages.php">🚗 Covoiturages</a>
        <a href="login.php">🔐 Connexion</a>
        <a href="contact.php">📞 Contact</a>
        <a href="user-space.php">👤 Mon Espace</a>
    </nav>

    <div class="user-section">
        <h2>⚙️ Mes Préférences</h2>
        <div class="form-section" style="margin-top: 40px; padding: 20px; border: 2px solid #1f6b4e; border-radius: 10px;">
            <h3 style="color: #1f6b4e;">🚗 Créer un nouveau covoiturage</h3>
            
            <form action="config/create-ride.php" method="POST">
                <div style="margin: 10px 0;">
                    <label for="departure">Ville de départ:</label>
                    <input type="text" id="departure" name="departure" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 10px 0;">
                    <label for="arrival">Ville d'arrivée:</label>
                    <input type="text" id="arrival" name="arrival" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 10px 0;">
                    <label for="date_time">Date et heure de départ:</label>
                    <input type="datetime-local" id="date_time" name="date_time" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 10px 0;">
                    <label for="seats">Nombre de places disponibles:</label>
                    <input type="number" id="seats" name="seats" min="1" max="8" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 10px 0;">
                    <label for="price">Prix par passager (en crédits):</label>
                    <input type="number" id="price" name="price" min="1" step="1" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 10px 0;">
                    <label for="vehicle_id">Sélectionner votre véhicule:</label>
                    <select id="vehicle_id" name="vehicle_id" required style="width: 100%; padding: 8px; margin: 5px 0;">
    <option value="">-- Choisir un véhicule --</option>
    <?php
    if (isset($_SESSION['utilisateur_id'])) {
        $user_id = $_SESSION['utilisateur_id'];
        $sql = "SELECT v.voiture_id, v.modele 
                FROM voiture v 
                JOIN gere g ON v.voiture_id = g.voiture_id 
                WHERE g.utilisateur_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($vehicle = $result->fetch_assoc()) {
                echo "<option value='{$vehicle['voiture_id']}'>";
                echo $vehicle['modele'];
                echo "</option>";
            }
        } else {
            echo "<option value='' disabled>Aucun véhicule - ajoutez-en un d'abord</option>";
        }
    }
    ?>
</select>
                </div>
                
                <div style="margin: 10px 0;">
                    <label for="preferences">Préférences supplémentaires:</label>
                    <textarea id="preferences" name="preferences" placeholder="Musique, arrêts, etc..." style="width: 100%; padding: 8px; margin: 5px 0; height: 80px;"></textarea>
                </div>
                
                <button type="submit" style="background-color: #1f6b4e; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px;">Créer le covoiturage</button>
            </form>
        </div>
        
        <div class="user-section">
            <div class="profile-info">
                <div class="profile-avatar">👤</div>
                <div>
                    Espace de <?php echo $_SESSION['user']; ?>.
                    <p><span class="credits-badge">20 crédits disponibles (Bienvenue, <?php echo $_SESSION['user']; ?>!) </span></p>
                    <p>Membre depuis: Décembre 2024</p>
                </div>
            </div>
        </div>

        <div class="user-section">
            <h2>🚗 Mes Véhicules</h2>
            <p><em>Aucun véhicule enregistré</em></p>
            
            <h3>Ajouter un véhicule</h3>
            <form class="vehicle-form" action="config/add-vehicle.php" method="POST">
                <label>Plaque d'immatriculation:</label>
                <input type="text" name="plaque" value="CZ-536-ET" required>
                
                <label>Date de première immatriculation:</label>
                <input type="date" name="date_immat" value="2020-11-20" required>
                
                <label>Marque du véhicule:</label>
                <input type="text" name="marque" value="BMW" required>
                
                <label>Modèle:</label>
                <input type="text" name="modele" value="118d" required>
                
                <label>Couleur:</label>
                <input type="text" name="couleur" value="Bleu" required>
                
                <label>Nombre de places:</label>
                <input type="number" name="places" value="5" min="2" max="9" required>
                
                <label>Énergie utilisée:</label>
                <select name="energie" required>
                    <option value="">Choisir...</option>
                    <option value="electrique">Électrique</option>
                    <option value="essence">Essence</option>
                    <option value="diesel" selected>Diesel</option> 
                    <option value="hybride">Hybride</option>
                </select>
                
                <button type="submit">✅ Enregistrer le véhicule</button>
            </form>
        </div>
        
        <div class="preferences">
            <p><input type="checkbox"> ✅ Accepte les fumeurs</p>
            <p><input type="checkbox"> ✅ Accepte les animaux</p>
            <p><input type="checkbox"> 🔇 Préfère le silence</p>
            <p><input type="checkbox"> 💬 Aime discuter</p>
            <p><input type="checkbox"> 🎵 Musique autorisée</p>
            <p><input type="checkbox"> 🍿 Collations autorisées</p>
            <button>💾 Enregistrer les préférences</button>
        </div>
    </div>
    
    <div class="user-section">
        <h2>📊 Mon Activité</h2>
        <p><strong>Trajets conduits:</strong> 0</p>
        <p><strong>Trajets participés:</strong> 0</p>
        <p><strong>Note moyenne:</strong> ⭐⭐⭐⭐⭐ (5.0)</p>
    </div>
</body>
</html>