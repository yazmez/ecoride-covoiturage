<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturage Écologique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f3f2;
            background-image: url(https://images.unsplash.com/photo-1698464795984-9da9eb4a99cd?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1931);
        }
        .header {
            background-color: #0b552b;
            color: rgb(178, 212, 233);
            padding: 20px;
            text-align: center;
        }
        .search-bar {
            background: rgb(191, 189, 240);
            padding: 20px;
            text-align: center;
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
    
    <div class="header">
        <h1>EcoRide</h1>
        <p>Votre plateforme de covoiturage écologique</p>
    </div>
    
    <div class="search-bar">
        <h2>Trouvez votre trajet</h2>
        <form action="covoiturages.php" method="GET">
            <input type="text" name="depart" placeholder="Départ" required>
            <input type="text" name="arrivee" placeholder="Arrivée" required>
            <input type="date" name="date" required>
            <button type="submit">Rechercher</button>
        </form>
    </div>
</body>
</html>