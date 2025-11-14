<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>covoiturage - Ecoride</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f3f2;
            background-image: url(https://media.gettyimages.com/id/1542588811/fr/photo/an-electric-car-plugged-in-against-a-background-of-a-rural-location-at-sunset.jpg?s=2048x2048&w=gi&k=20&c=x0nmpQ23FFWcID4Gkkyfjtqv7phevHJ3nW2g6uJhW4U=);
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
        
        h1 {background: rgb(130, 243, 241);
                padding: 20px;
                margin: 20px;
                border-radius: 8px;
        }
        h2 {background: rgb(92, 49, 177);
                padding: 15px;
                margin-top: 15px;
                border-radius: 6px;
        }
        h3 {background: rgb(160, 241, 110);
                padding: 15px;
                margin-top: 15px;
                border-radius: 6px;}
                .ride-card {
    background: white;
    padding: 20px;
    margin: 15px;
    border-radius: 8px;
    border-left: 5px solid #2ecc71;}
.eco-badge {
    background: #2ecc71;
    color: white;
    padding: 5px 10px;
    border-radius: 15px;}
.non-eco-badge {
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 15px;}
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
    <h1>page des Covoiturages</h1>
    <p>cette page affichera les trajets disponible</p>
    <div>
    <h2>🔍 Rechercher un covoiturage</h2>
    <form method="GET" action="covoiturages.php">
        <input type="text" name="depart" placeholder="Ville de départ" style="padding: 8px; margin: 5px;" value="<?php echo isset($_GET['depart']) ? $_GET['depart'] : ''; ?>">
        <input type="text" name="arrivee" placeholder="Ville d'arrivée" style="padding: 8px; margin: 5px;" value="<?php echo isset($_GET['arrivee']) ? $_GET['arrivee'] : ''; ?>">
        <input type="date" name="date" style="padding: 8px; margin: 5px;" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
        <button type="submit">Rechercher</button>
    </form></div>
    <?php
    $conn = new mysqli('localhost', 'root', '', 'EcoRide');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);}
    $sql = "SELECT c.*, u.pseudo, u.nom, u.prenom, v.energie, v.modele, m.libelle as marque 
            FROM covoiturage c
            JOIN utilise ut ON c.covoiturage_id = ut.covoiturage_id
            JOIN voiture v ON ut.voiture_id = v.voiture_id
            JOIN detient d ON v.voiture_id = d.voiture_id
            JOIN marque m ON d.marque_id = m.marque_id
            JOIN gere g ON v.voiture_id = g.voiture_id
            JOIN utilisateur u ON g.utilisateur_id = u.utilisateur_id
            WHERE c.nb_place > 0 AND c.statut = 'planifié'";

    if (isset($_GET['depart']) && !empty($_GET['depart'])) {
        $sql .= " AND c.lieu_depart LIKE '%" . $conn->real_escape_string($_GET['depart']) . "%'";}
    if (isset($_GET['arrivee']) && !empty($_GET['arrivee'])) {
        $sql .= " AND c.lieu_arrivee LIKE '%" . $conn->real_escape_string($_GET['arrivee']) . "%'";
    }
    if (isset($_GET['date']) && !empty($_GET['date'])) {$sql .= " AND c.date_depart = '" . $conn->real_escape_string($_GET['date']) . "'";}

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $isEco = ($row['energie'] == 'Électrique');
            ?>
            <div class="ride-card">
                <h3>🚗 <?php echo $row['lieu_depart']; ?> → <?php echo $row['lieu_arrivee']; ?></h3>
                <p><strong>Conducteur:</strong> <?php echo $row['pseudo']; ?> ⭐⭐⭐⭐☆ (4.0)</p>
                <p><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($row['date_depart'])); ?> - <?php echo date('H:i', strtotime($row['heure_depart'])); ?></p>
                <p><strong>Places:</strong> <?php echo $row['nb_place']; ?> restante(s)</p>
                <p><strong>Prix:</strong> <?php echo $row['prix_personne']; ?>€</p>
                <p><strong>Véhicule:</strong> <?php echo $row['marque'] . ' ' . $row['modele']; ?></p>
                
                <?php if ($isEco): ?>
                    <p><strong>Écologique:</strong> <span class="eco-badge">✅ Voiture électrique</span></p>
                <?php else: ?>
                    <p><strong>Écologique:</strong> <span class="non-eco-badge">⛽ Non-écologique</span></p>
                <?php endif; ?>
                
                <a href="vue-detaillee.php" style="text-decoration: none;">
                    <button style="padding: 8px 15px; background: #2e8b57; color: white; border: none; border-radius: 4px;">
                        Voir les détails
                    </button>
                </a>
            </div>
            <?php
        }} else {
   echo "<div style='background: white; padding: 20px; margin: 20px; border-radius: 8px;'>";
        echo "<p>Aucun covoiturage trouvé pour votre recherche.</p>";
        echo "</div>";}
    
    $conn->close();
    ?>
</body> 
</html>
    