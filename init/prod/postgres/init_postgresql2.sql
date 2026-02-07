
INSERT INTO users
(roles, email, "password", "pseudo", nom, prenom, telephone, adresse, date_naissance, photo, is_verified, note, credits)
VALUES('["ROLE_ADMIN"]', 'admin@test.com', '$2y$13$BQbmSs93.tlthbIlth3MluUNBlelfzn99p8d8K3rM.rRKW.yFhUbi', 'chef', 'Duride', 'Joe', '564546456', 'ljqhedflked', '1998-12-12', '0.png',  true, null, 20);

INSERT INTO users
(roles, email, "password", "pseudo", nom, prenom, telephone, adresse, date_naissance, photo, is_verified, note, credits)
VALUES('["ROLE_EMPLOYEE"]', 'employee@test.com', '$2y$13$BQbmSs93.tlthbIlth3MluUNBlelfzn99p8d8K3rM.rRKW.yFhUbi', 'employee', 'Empl', 'Joe', '564546456', 'ljqhedflked', '1998-12-12', '0.png',  true, null, 20);

INSERT INTO users
(roles, email, "password", "pseudo", nom, prenom, telephone, adresse, date_naissance, photo, is_verified, note, credits)
VALUES('["ROLE_USER"]', 'user1@test.com', '$2y$13$BQbmSs93.tlthbIlth3MluUNBlelfzn99p8d8K3rM.rRKW.yFhUbi', 'user1', 'Doe', 'Joe', '564546456', '2 rue de la Gare', '1998-12-13', '0.png',  true, null, 20);

INSERT INTO users
(roles, email, "password", "pseudo", nom, prenom, telephone, adresse, date_naissance, photo, is_verified, note, credits)
VALUES('["ROLE_USER"]', 'user2@test.com', '$2y$13$BQbmSs93.tlthbIlth3MluUNBlelfzn99p8d8K3rM.rRKW.yFhUbi', 'user2', 'Dupont', 'Gilles', '564546456', '32 rue de la Victoire', '1998-12-14', '0.png',  true, null, 20);

INSERT INTO users
(roles, email, "password", "pseudo", nom, prenom, telephone, adresse, date_naissance, photo, is_verified, note, credits)
VALUES('["ROLE_USER"]', 'user3@test.com', '$2y$13$BQbmSs93.tlthbIlth3MluUNBlelfzn99p8d8K3rM.rRKW.yFhUbi', 'user3', 'Doe', 'Joe', '564546456', '51 rue de la Paix', '1998-12-15', '0.png',  true, null, 20);

INSERT INTO energie (libelle, ecologique) VALUES ('Essence', false);
INSERT INTO energie (libelle, ecologique) VALUES ('Diesel', false);
INSERT INTO energie (libelle, ecologique) VALUES ('Electrique', true);
INSERT INTO energie (libelle, ecologique) VALUES ('Hybride', true);

INSERT INTO marque (libelle) VALUES ('Renault');
INSERT INTO marque (libelle) VALUES ('Peugeot');
INSERT INTO marque (libelle) VALUES ('Citroën');
INSERT INTO marque (libelle) VALUES ('Tesla');

INSERT INTO ville (libelle) VALUES ('PARIS');
INSERT INTO ville (libelle) VALUES ('LYON');
INSERT INTO ville (libelle) VALUES ('MARSEILLE');
INSERT INTO ville (libelle) VALUES ('TOULOUSE');
INSERT INTO ville (libelle) VALUES ('NICE');
INSERT INTO ville (libelle) VALUES ('NANTES');
INSERT INTO ville (libelle) VALUES ('STRASBOURG');
INSERT INTO ville (libelle) VALUES ('MONTPELLIER');
INSERT INTO ville (libelle) VALUES ('BORDEAUX');
INSERT INTO ville (libelle) VALUES ('LILLE');

INSERT INTO voiture 
(marque_id, chauffeur_id, energie_id, modele, immatriculation, couleur, date_premiere_immatriculation, fumeur, animal) 
VALUES (1, 3, 1, 'Clio', 'AB-123-CD', 'Rouge', '2015-01-01', false, false);
INSERT INTO voiture 
(marque_id, chauffeur_id, energie_id, modele, immatriculation, couleur, date_premiere_immatriculation, fumeur, animal) 
VALUES (2, 4, 2, '208', 'EF-456-GH', 'Bleu', '2016-02-01', false, false);
INSERT INTO voiture 
(marque_id, chauffeur_id, energie_id, modele, immatriculation, couleur, date_premiere_immatriculation, fumeur, animal) 
VALUES (3, 5, 3, 'C3', 'IJ-789-KL', 'Blanc', '2017-03-01', false, false);
INSERT INTO voiture 
(marque_id, chauffeur_id, energie_id, modele, immatriculation, couleur, date_premiere_immatriculation, fumeur, animal) 
VALUES (4, 3, 4, 'Model 3', 'MN-012-OP', 'Noir', '2018-04-01', false, false);

INSERT INTO covoiturage (lieu_depart_id, lieu_arrivee_id, voiture_id, date_depart, heure_depart, date_arrivee, heure_arrivee, statut, nb_place, prix_personne) 
VALUES (1, 2, 1, '2024-07-01', '10:00:00', '2024-07-01', '15:00:00', 'planifié', 3, 5.00);
INSERT INTO covoiturage (lieu_depart_id, lieu_arrivee_id, voiture_id, date_depart, heure_depart, date_arrivee, heure_arrivee, statut, nb_place, prix_personne) 
VALUES (2, 3, 2, '2024-07-02', '09:00:00', '2024-07-02', '14:00:00', 'planifié', 2, 7.50);
INSERT INTO covoiturage (lieu_depart_id, lieu_arrivee_id, voiture_id, date_depart, heure_depart, date_arrivee, heure_arrivee, statut, nb_place, prix_personne) 
VALUES (3, 4, 3, '2024-07-03', '10:00:00', '2024-07-03', '12:00:00', 'planifié', 3, 4.00);
