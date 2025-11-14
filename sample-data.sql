USE EcoRide;
INSERT INTO utilisateur (nom, prenom, email, password, pseudo, credit) VALUES
('son', 'goku', 'son.goku@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'JeanEco', 50),
('lola', 'bigbo', 'lola.bigbo@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'MarieGreen', 45),
('ayoub', 'dinckelson', 'ayoub.dinckelson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.ogi', 'PierreEcoDrive', 60);

INSERT INTO marque (libelle) VALUES
('Tesla'), ('Renault'), ('Nissan'), ('Peugeot');

INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation) VALUES
('Model 3', 'AZ-146-BY', 'Électrique', 'Blanc', '2022-05-15'),
('Zoe', 'BD-987-XZ', 'Électrique', 'Bleu', '2021-08-20'),
('Leaf', 'YM-205-AR', 'Électrique', 'Rouge', '2020-03-10'),
('208', 'HH-420-YY', 'Essence', 'Gris', '2023-01-05');

INSERT INTO detient (voiture_id, marque_id) VALUES
(1, 1), (2, 2), (3, 3), (4, 4);

INSERT INTO gere (utilisateur_id, voiture_id) VALUES
(1, 1), (2, 2), (3, 3), (1, 4);

INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, statut, nb_place, prix_personne) VALUES
('2025-11-15', '08:00:00', 'Paris Gare de Lyon', '2025-11-15', '12:00:00', 'Gare agde', 'planifié', 3, 25.00),
('2025-11-16', '14:30:00', 'Lyon Part-Dieu', '2025-11-16', '18:45:00', 'gare de charleville mezières', 'planifié', 2, 30.00),
('2025-11-17', '07:15:00', 'Marseille Saint-Charles', '2025-11-17', '11:30:00', 'troyes Ville', 'planifié', 4, 15.00),
('2025-11-18', '16:00:00', 'Bordeaux Saint-Jean', '2025-11-18', '20:15:00', 'Toulouse Matabiau', 'planifié', 1, 20.00);

INSERT INTO utilise (voiture_id, covoiturage_id) VALUES
(1, 1), (2, 2), (3, 3), (4, 4);

INSERT INTO avis (commentaire, note, statut) VALUES
('Très bon conducteur, ponctuel et sympathique!', '5', 'validé'),
('le mec arrete pas de parler', '3', 'validé'),
('Excellent covoiturage, je recommande', '5', 'validé');

INSERT INTO depose (utilisateur_id, avis_id) VALUES
(2, 1), (3, 2), (1, 3);