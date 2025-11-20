
CREATE SEQUENCE IF NOT EXISTS marque_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS marque (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('marque_seq'),
    libelle varchar (50) NOT NULL
);
ALTER SEQUENCE marque_seq OWNED BY marque.id;

CREATE SEQUENCE IF NOT EXISTS energie_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS energie (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('energie_seq'),
    libelle varchar (50) NOT NULL,
    ecologique bool NOT NULL
);
ALTER SEQUENCE energie_seq OWNED BY energie.id;

CREATE SEQUENCE IF NOT EXISTS ville_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS ville (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('ville_seq'),
    libelle varchar (50) NOT NULL
);
ALTER SEQUENCE ville_seq OWNED BY ville.id;

CREATE SEQUENCE IF NOT EXISTS users_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS users (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('users_seq'),
    email varchar (100) unique NOT NULL,
    roles json not null,
    password varchar (255) NOT NULL,
    pseudo varchar (50) unique NOT NULL,
    nom varchar (100) NOT NULL,
    prenom varchar (100) NOT NULL,
    telephone varchar (16) NOT NULL,
    adresse varchar (255) NOT NULL,
    date_naissance date NOT NULL,
    photo varchar (255) NOT NULL,
    is_verified bool NOT null,
    note int,
    credits int NOT NULL
);
ALTER SEQUENCE users_seq OWNED BY users.id;

CREATE SEQUENCE IF NOT EXISTS voiture_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS voiture (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('voiture_seq'),
    marque_id BIGINT NOT NULL,
    chauffeur_id BIGINT NOT NULL,
    energie_id BIGINT NOT NULL,
    modele varchar (50) NOT NULL,
    immatriculation varchar (20) NOT NULL,
    couleur varchar (50) NOT NULL,
    date_premiere_immatriculation date NOT NULL,
    fumeur bool,
    animal bool,
    FOREIGN KEY (marque_id) REFERENCES marque,
    FOREIGN KEY (chauffeur_id) REFERENCES users,
    FOREIGN KEY (energie_id) REFERENCES energie
);
ALTER SEQUENCE voiture_seq OWNED BY voiture.id;

CREATE SEQUENCE IF NOT EXISTS covoiturage_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS covoiturage (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('covoiturage_seq'),
    lieu_depart_id BIGINT NOT NULL,
    lieu_arrivee_id BIGINT NOT NULL,
    voiture_id BIGINT NOT NULL,
    date_depart date NOT NULL,
    heure_depart time NOT NULL,
    date_arrivee date NOT NULL,
    heure_arrivee time NOT NULL,
    statut varchar (50) NOT NULL,
    nb_place int NOT NULL,
    prix_personne float NOT NULL,
    FOREIGN KEY (lieu_depart_id) REFERENCES ville,
    FOREIGN KEY (lieu_arrivee_id) REFERENCES ville,
    FOREIGN KEY (voiture_id) REFERENCES voiture
);
ALTER SEQUENCE covoiturage_seq OWNED BY covoiturage.id;

CREATE SEQUENCE IF NOT EXISTS participation_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE TABLE IF NOT EXISTS participation (
    id BIGINT PRIMARY KEY DEFAULT NEXTVAL('participation_seq'),
    passager_id BIGINT NOT NULL,
    commentaire text,
    statut varchar (50) NOT NULL,
    covoiturage_id BIGINT NOT NULL,
    FOREIGN KEY (covoiturage_id) REFERENCES covoiturage,
    FOREIGN KEY (passager_id) REFERENCES users
);
ALTER SEQUENCE participation_seq OWNED BY participation.id;

INSERT INTO users
(roles, email, "password", "pseudo", nom, prenom, telephone, adresse, date_naissance, photo, is_verified, note, credits)
VALUES('["ROLE_ADMIN"]', 'admin@test.com', '$2y$13$BQbmSs93.tlthbIlth3MluUNBlelfzn99p8d8K3rM.rRKW.yFhUbi', 'chef', 'Duride', 'Joe', '564546456', 'ljqhedflked', '1998-12-12', '0.png',  true, null, 20);
