CREATE DATABASE IF NOT EXISTS EcoRide CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE EcoRide;

CREATE TABLE configuration (
    id_configuration INT PRIMARY KEY AUTO_INCREMENT
);

CREATE TABLE parametre (
    parametre_id INT PRIMARY KEY AUTO_INCREMENT,
    propriete VARCHAR(50) NOT NULL,
    valeur VARCHAR(50) NOT NULL
);

CREATE TABLE dispose_config_param (
    id_configuration INT,
    parametre_id INT,
    PRIMARY KEY (id_configuration, parametre_id),
    FOREIGN KEY (id_configuration) REFERENCES configuration(id_configuration),
    FOREIGN KEY (parametre_id) REFERENCES parametre(parametre_id)
);

CREATE TABLE config_param_vide (
    id_configuration INT PRIMARY KEY,
    parametre_vide_id INT,
    FOREIGN KEY (id_configuration) REFERENCES configuration(id_configuration)
);

CREATE TABLE utilisateur (
    utilisateur_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(50),
    adresse VARCHAR(50),
    date_naissance VARCHAR(50),
    photo BLOB,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    credit INT DEFAULT 20
);

CREATE TABLE param_vide_utilisateur (
    parametre_vide_id INT,
    utilisateur_id INT,
    PRIMARY KEY (parametre_vide_id, utilisateur_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id)
);

CREATE TABLE role (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE possede (
    utilisateur_id INT,
    role_id INT,
    PRIMARY KEY (utilisateur_id, role_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

CREATE TABLE voiture (
    voiture_id INT PRIMARY KEY AUTO_INCREMENT,
    modele VARCHAR(50) NOT NULL,
    immatriculation VARCHAR(50) UNIQUE NOT NULL,
    energie VARCHAR(50) NOT NULL,
    couleur VARCHAR(50) NOT NULL,
    date_premiere_immatriculation VARCHAR(50) NOT NULL
);

CREATE TABLE gere (
    utilisateur_id INT,
    voiture_id INT,
    PRIMARY KEY (utilisateur_id, voiture_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (voiture_id) REFERENCES voiture(voiture_id)
);

CREATE TABLE marque (
    marque_id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE detient (
    voiture_id INT PRIMARY KEY,
    marque_id INT NOT NULL,
    FOREIGN KEY (voiture_id) REFERENCES voiture(voiture_id),
    FOREIGN KEY (marque_id) REFERENCES marque(marque_id)
);

CREATE TABLE covoiturage (
    covoiturage_id INT PRIMARY KEY AUTO_INCREMENT,
    date_depart DATE NOT NULL,
    heure_depart TIME NOT NULL,
    lieu_depart VARCHAR(50) NOT NULL,
    date_arrivee DATE NOT NULL,
    heure_arrivee TIME NOT NULL,
    lieu_arrivee VARCHAR(50) NOT NULL,
    statut VARCHAR(50) DEFAULT 'planifié',
    nb_place INT NOT NULL,
    prix_personne FLOAT NOT NULL
);

CREATE TABLE participe (
    utilisateur_id INT,
    covoiturage_id INT,
    est_conducteur BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (utilisateur_id, covoiturage_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id)
);

CREATE TABLE avis (
    avis_id INT PRIMARY KEY AUTO_INCREMENT,
    commentaire VARCHAR(50),
    note VARCHAR(50),
    statut VARCHAR(50) DEFAULT 'en attente'
);

CREATE TABLE depose (
    utilisateur_id INT,
    avis_id INT,
    PRIMARY KEY (utilisateur_id, avis_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (avis_id) REFERENCES avis(avis_id)
);
CREATE TABLE utilise (
    voiture_id INT,
    covoiturage_id INT,
    PRIMARY KEY (voiture_id, covoiturage_id),
    FOREIGN KEY (voiture_id) REFERENCES voiture(voiture_id),
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id)
);
CREATE TABLE employe (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP);
ALTER TABLE utilisateur ADD COLUMN suspended BOOLEAN DEFAULT FALSE;
ALTER TABLE employe ADD COLUMN suspended BOOLEAN DEFAULT FALSE;