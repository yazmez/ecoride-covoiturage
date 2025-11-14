<?php 
session_start();

// Get the ride ID from URL and connect to database
$ride_id = isset($_GET['ride_id']) ? intval($_GET['ride_id']) : 1;

require_once 'config/config.php';

// Fetch the specific ride details from database
$sql = "SELECT c.*, u.pseudo, u.nom, u.prenom, v.energie, v.modele, m.libelle as marque 
        FROM covoiturage c
        JOIN utilise ut ON c.covoiturage_id = ut.covoiturage_id
        JOIN voiture v ON ut.voiture_id = v.voiture_id
        JOIN detient d ON v.voiture_id = d.voiture_id
        JOIN marque m ON d.marque_id = m.marque_id
        JOIN gere g ON v.voiture_id = g.voiture_id
        JOIN utilisateur u ON g.utilisateur_id = u.utilisateur_id
        WHERE c.covoiturage_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ride_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $ride = $result->fetch_assoc();
} else {
    // If ride not found, show error message
    echo "<div style='background: red; color: white; padding: 20px; margin: 20px;'>";
    echo "<h2>❌ Covoiturage non trouvé</h2>";
    echo "<p>Le trajet demandé n'existe pas.</p>";
    echo "<a href='covoiturages.php' style='color: white;'>← Retour aux covoiturages</a>";
    echo "</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du covoiturage - EcoRide</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        nav {
            background: #1f6b4e;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .details-du-covoiturage {
            background: rgb(130, 243, 241);
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
        }
        .preferences-du-conducteur {
            background: rgb(200, 200, 200);
            padding: 15px;
            margin-top: 15px;
            border-radius: 6px;
        }
        .avis-du-conducteur {
            background: rgb(180, 180, 180);
            padding: 15px;
            margin-top: 15px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">🏠 Accueil</a>
        <a href="covoiturages.php">🚗 Covoiturages</a>
        <a href="login.php">🔐 Connexion</a>
        <a href="contact.php">📞 Contact</a>
        <a href="user-space.php">👤 Mon Espace</a> 
    </nav>
    
    <div class="details-du-covoiturage">
        <h1>Détails du Covoiturage: <?php echo $ride['lieu_depart']; ?> → <?php echo $ride['lieu_arrivee']; ?></h1>
        <p><strong>Conducteur:</strong> <?php echo $ride['pseudo']; ?> ⭐⭐⭐⭐☆ (4.0)</p>
        <p><strong>Date et Heure:</strong> <?php echo date('d F Y', strtotime($ride['date_depart'])); ?> - <?php echo date('H:i', strtotime($ride['heure_depart'])); ?></p>
        <p><strong>Lieu de Départ:</strong> <?php echo $ride['lieu_depart']; ?></p>
        <p><strong>Lieu d'Arrivée:</strong> <?php echo $ride['lieu_arrivee']; ?></p>
        <p><strong>Places Disponibles:</strong> <?php echo $ride['nb_place']; ?></p>
        <p><strong>Prix par Place:</strong> <?php echo $ride['prix_personne']; ?>€</p>
        <p><strong>Type de Véhicule:</strong> <?php echo $ride['marque'] . ' ' . $ride['modele']; ?> - <?php echo $ride['energie']; ?></p>
        
        <div class="preferences-du-conducteur">
            <h2>Préférences du Conducteur</h2>
            <ul>
                <li>Non-fumeur</li>
                <li>Pas d'animaux</li>
                <li>Musique douce préférée</li>
            </ul>
        </div>
        
        <div class="avis-du-conducteur">
            <h2>Avis des Passagers Précédents</h2>
            <p>"Conducteur très prudent et sympathique. Le trajet était agréable!" - Marie L.⭐⭐⭐☆☆</p>
            <p>"Voiture propre et confortable. Je recommande vivement!" - Amayass S.⭐⭐⭐⭐⭐</p>
            <p>"Ponctuel et respectueux" - Lytissia S.⭐⭐⭐⭐☆</p>
        </div>
        
        <div class="reservation-section" style="margin-top: 20px; padding: 20px; background: #e8f5e8; border-radius: 8px;">
            <h2>Réserver ce trajet</h2>
            
            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                <div class="booking-info">
                    <p><strong>Prix:</strong> <?php echo $ride['prix_personne']; ?> crédits</p>
                    <p><strong>Places disponibles:</strong> <?php echo $ride['nb_place']; ?></p>
                    
                    <form action="config/book-ride.php" method="POST">
                        <input type="hidden" name="ride_id" value="<?php echo $ride_id; ?>">
                        <input type="hidden" name="price" value="<?php echo $ride['prix_personne']; ?>">
                        <button type="submit" style="background-color: #1f6b4e; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                            🎫 Réserver ce trajet
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p style="color: #721c24;"><strong>🔒 Connectez-vous pour réserver ce trajet</strong></p>
                    <a href="login.php" style="background-color: #1f6b4e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                        Se connecter
                    </a>
                    <a href="register.php" style="background-color: #2e8b57; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;">
                        Créer un compte
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>