-- =========================================================
-- Base de données : My Series Companion
-- Niveau : BTS SIO SLAM
-- Compatible MySQL / MariaDB
-- =========================================================

CREATE DATABASE IF NOT EXISTS my_series_companion
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE my_series_companion;

-- Suppression des tables pour pouvoir réimporter le fichier.
DROP TABLE IF EXISTS regarder;
DROP TABLE IF EXISTS episode;
DROP TABLE IF EXISTS saison;
DROP TABLE IF EXISTS serie;
DROP TABLE IF EXISTS personne;

-- ---------------------------------------------------------
-- Table SERIE
-- ---------------------------------------------------------
CREATE TABLE serie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    resume TEXT NULL,
    vignette VARCHAR(255) NULL,
    date_sortie DATE NOT NULL
);

-- ---------------------------------------------------------
-- Table SAISON
-- Une saison appartient à une seule série.
-- ---------------------------------------------------------
CREATE TABLE saison (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    resume TEXT NULL,
    vignette VARCHAR(255) NULL,
    date_sortie DATE NOT NULL,
    serie_id INT NOT NULL,

    CONSTRAINT fk_saison_serie
        FOREIGN KEY (serie_id)
        REFERENCES serie(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ---------------------------------------------------------
-- Table EPISODE
-- Un épisode appartient à une seule saison.
-- ---------------------------------------------------------
CREATE TABLE episode (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    resume TEXT NULL,
    vignette VARCHAR(255) NULL,
    date_sortie DATE NOT NULL,
    duree INT NULL,
    saison_id INT NOT NULL,

    CONSTRAINT fk_episode_saison
        FOREIGN KEY (saison_id)
        REFERENCES saison(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ---------------------------------------------------------
-- Table PERSONNE
-- Elle est conservée car elle apparaît dans la modélisation
-- fournie dans le sujet.
-- ---------------------------------------------------------
CREATE TABLE personne (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL
);

-- ---------------------------------------------------------
-- Table REGARDER
-- Association entre une personne et un épisode.
-- Une personne peut regarder plusieurs épisodes.
-- Un épisode peut être regardé par plusieurs personnes.
-- ---------------------------------------------------------
CREATE TABLE regarder (
    personne_id INT NOT NULL,
    episode_id INT NOT NULL,

    PRIMARY KEY (personne_id, episode_id),

    CONSTRAINT fk_regarder_personne
        FOREIGN KEY (personne_id)
        REFERENCES personne(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_regarder_episode
        FOREIGN KEY (episode_id)
        REFERENCES episode(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
