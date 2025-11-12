<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>vue détaillée du covoiturage</title>
<style>
 body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;}
        nav {background-color: aquamarine;
            padding: 15px;
            text-align: center;}
        nav a {color: white;
            margin: 0 10px;
            text-decoration:dashed;}
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
    <nav style="background:#1f6b4e; padding: 20px; text-align: center;">
        <a href="index.php">🏠 Accueil</a>
        <a href="covoiturages.php">🚗 Covoiturages</a>
        <a href="login.php">🔐 Connexion</a>
        <a href="contact.php">📞 Contact</a>
        <a href="user-space.php">👤 Mon Espace</a> 
    </nav>
    <div class="details-du-covoiturage">
        <h1>Détails du Covoiturage: Paris → Lyon</h1>
        <p><strong>Conducteur:</strong> Yazid M. ⭐⭐⭐⭐☆ (4.0)</p>
        <p><strong>Date et Heure:</strong> 15 Décembre 2025 - 08:00</p>
        <p><strong>Lieu de Départ:</strong> Gare de Lyon, Paris</p>
        <p><strong>Lieu d'Arrivée:</strong> Gare Part-Dieu, Lyon</p>
        <p><strong>Places Disponibles:</strong> 3</p>
        <p><strong>Prix par Place:</strong> 25€</p>
        <p><strong>Type de Véhicule:</strong> Voiture électrique - Tesla Model 3</p>
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
            <p>"Jean est un conducteur très prudent et sympathique. Le trajet était agréable!" - Marie L.⭐⭐⭐☆☆</p>
            <p>"Voiture propre et confortable. Je recommande vivement!" - Amayass S.⭐⭐⭐⭐⭐</p>
            <p> "ponctuel et respecteux" Lytissia S.⭐⭐⭐⭐☆</p>
        </div>
        </body>
</html>